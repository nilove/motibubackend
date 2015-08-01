<?php namespace Motibu\Presenters;

use Laracasts\Presenter\Presenter;

class ClientPresenter extends Presenter {

    public function logo_url()
    {
        if(strlen($this->logo_filename))
            return url('/uploads/img/'.$this->logo_filename);

        return '';
    }
}