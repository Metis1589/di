<?php
namespace common\enums;

class CallStatus extends BaseEnum {
    const Queued     = 'queued';
    const Ringing    = 'ringing';
    const InProgress = 'in-progress';
    const Canceled   = 'canceled';
    const Completed  = 'completed';
    const Failed     = 'failed';
    const Busy       = 'busy';
    const NoAnswer   = 'no-answer';
}