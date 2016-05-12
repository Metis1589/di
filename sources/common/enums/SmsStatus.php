<?php
namespace common\enums;

class SmsStatus extends BaseEnum {
    const Queued   = 'queued';
    const Sending  = 'sending';
    const Sent     = 'sent';
    const Failed   = 'failed';
    const Received = 'received';
}