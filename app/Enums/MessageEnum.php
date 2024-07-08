<?php

namespace App\Enums;

enum MessageEnum: string
{
    case TEXT   = 'text';
    case IMAGE   = 'image';
    case MEDIA   = 'media';
    case LOCATION   = 'location';
    case AUDIO   = 'audio';
    case VIDEO   = 'video';
    case DOCUMENT   = 'document';
    case CONTACTS   = 'contacts';
    case CONTACT   = 'contact';
    case BUTTON   = 'button';
   
}
