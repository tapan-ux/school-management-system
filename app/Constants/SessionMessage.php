<?php

namespace App\Constants;

class SessionMessage
{
    public function messages($title, $user = 'Data'){

        switch($title){
            case 'create':
                return $user.' created Successfully';
                break;

            case 'create_error':
                return 'Error in creating '.$user;
                break;

            case 'update':
                return $user.' updated Successfully';
                break;

            case 'update_error':
                return 'Error in updating '.$user;
                break;

            case 'delete':
                return $user.' deleted Successfully';
                break;
            case 'delete_error':
                return 'Error in deleting '.$user;
                break;

            case 'retrieve':
                return $user.' retrieved Successfully';
                break;
            case 'retrieve_error':
                return 'Error in retrieving '.$user;
                break;

            default:
                return $title;
        }
    }
}
