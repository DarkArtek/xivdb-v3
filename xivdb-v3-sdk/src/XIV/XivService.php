<?php

namespace XIV;

use XIV\Services\{
    API, Comments, Data, DevApps, Email, Feedback, FeedbackComment, Mognet, Page, Sync, Translation, XIVSYNC
};

class XivService
{
    /** @var API */
    public $API;
    /** @var MogNet */
    public $Mognet;
    /** @var Data */
    public $Data;
    /** @var Sync */
    public $Sync;
    /** @var Page */
    public $Page;
    /** @var Email */
    public $Email;
    /** @var DevApps */
    public $DevApps;
    /** @var Comments */
    public $Comments;
    /** @var Translation */
    public $Translation;
    /** @var Feedback */
    public $Feedback;
    /** @var FeedbackComment */
    public $FeedbackComment;
    /** @var XIVSYNC */
    public $XIVSYNC;
    
    function __construct()
    {
        Config::init();
        
        $this->API              = new API();
        $this->Mognet           = new Mognet();
        $this->Data             = new Data();
        $this->Sync             = new Sync();
        $this->Page             = new Page();
        $this->Email            = new Email();
        $this->DevApps          = new DevApps();
        $this->Comments         = new Comments();
        $this->Translation      = new Translation();
        $this->Feedback         = new Feedback();
        $this->FeedbackComment  = new FeedbackComment();
        $this->XIVSYNC          = new XIVSYNC();
    }
}
