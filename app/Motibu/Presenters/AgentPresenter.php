<?php namespace Motibu\Presenters;


use Laracasts\Presenter\Presenter;

class AgentPresenter extends Presenter {

    public function profile_pic_url()
    {
        if(strlen($this->profile_pic_filename))
            return url('/uploads/img/'.$this->profile_pic_filename);

        return '';
    }
}