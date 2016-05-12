<?php
/**
 * @author jarik <jarik1112@gmail.com>
 * @date   7/13/15
 * @time   6:44 PM
 */

namespace common\components;


use yii\base\Component;
use Yii;


class SshService extends Component
{
    /**
     * @var string Host to connect
     */
    public $host;

    /**
     * @var int Port default 22
     */
    public $port = 22;

    /**
     * @var string User name to login
     */
    public $user;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $publicKey;

    /**
     * @var string
     */
    public $privateKey;

    public $passphrase;
    protected $connection;

    private $publicFilePath;

    private $privateFilePath;


    public function init()
    {
        $this->connection = ssh2_connect($this->host, $this->port);
        if ($this->connection === false) {
            throw new \Exception('SshService: Can\'t connect to host:' . $this->host);
        } else {
            Yii::trace('Ssh service connected');
        }
        // Auth
        // by user name and password
        if($this->password && !($this->publicKey && $this->privateKey)) {
            if (ssh2_auth_password($this->connection, $this->user, $this->password)) {
                Yii::trace('Ssh service authenticated by pass');
            } else {
                throw new \Exception('SshService: Can\'t authenticate by password user:' . $this->user);
            }
        } elseif ($this->password && $this->publicKey && $this->privateKey){
            $this->publicFilePath = $this->handleKeyFile($this->publicKey);
            $this->privateFilePath = $this->handleKeyFile($this->privateKey);
            @ssh2_auth_password($this->connection, $this->user, $this->password);
            if(ssh2_auth_pubkey_file($this->connection,$this->user,$this->publicFilePath,$this->privateFilePath)){
                Yii::trace('Ssh service authenticated by pass');
            }else{
                throw new \Exception('SshService: Can\'t authenticate by public key file user:' . $this->user);
            }
        } elseif ($this->publicKey && $this->privateKey){
            $this->publicFilePath = $this->handleKeyFile($this->publicKey);
            $this->privateFilePath = $this->handleKeyFile($this->privateKey);
            if(ssh2_auth_pubkey_file($this->connection,$this->user,$this->publicFilePath,$this->privateFilePath,$this->passphrase?:null)){
                Yii::trace('Ssh service authenticated by pass');
            }else{
                throw new \Exception('SshService: Can\'t authenticate by public key file user:' . $this->user);
            }
        }else{
            throw new \Exception('Configuration error. Not all required properties are set');
        }

    }

    /**
     * Handle key content
     * store content on disk
     *
     * @param string $content
     * @return string file path
     */
    private function handleKeyFile($content)
    {
        $file = tempnam(sys_get_temp_dir(),md5(microtime()*mt_rand(1,9)));
        chmod($file,0600);
        file_put_contents($file,$content);
        return $file;
    }
    /**
     * Upload file to remote host
     *
     * @param $localPath
     * @param $remotePath
     * @return bool
     */
    public function uploadFile($localPath, $remotePath)
    {
        Yii::trace('Send file local:'.$localPath.' remote:'.$remotePath,'sshservice');
        $sftp = ssh2_sftp($this->connection);
        $uploadContent = file_get_contents($localPath);
        $remotePath = '/'.ltrim($remotePath,'/');
        $t = file_put_contents("ssh2.sftp://{$sftp}{$remotePath}", $uploadContent);
        Yii::trace('Uploaded bytes:'.$t,'sshservice');
        return $t;
    }

    public function __destruct()
    {
        try {
            $this->privateFilePath && unlink($this->privateFilePath);
            $this->publicFilePath && unlink($this->publicFilePath);
        } catch (\Exception $e) {
            Yii::error($e->__toString(), 'sshservice');
        }
    }
}