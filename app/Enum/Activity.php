<?php

namespace App\Enum;

enum Activity: string
{
    case SEDENTARY = "Sedentary";
    case LIGHT = "Light";
    case MODERATE = "Moderate";
    case HEAVY = "Heavy";

}
