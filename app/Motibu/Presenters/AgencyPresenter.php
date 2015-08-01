<?php namespace Motibu\Presenters;


use Laracasts\Presenter\Presenter;

class AgencyPresenter extends Presenter {

    public function logo_url() {
        if(strlen($this->logo_filename))
            return url('/uploads/img/'.$this->logo_filename);

        return '';
    }

    public function banner_url()
    {
        if(strlen($this->banner_filename))
            return url('/uploads/img/'.$this->banner_filename);

        return '';
    }

}