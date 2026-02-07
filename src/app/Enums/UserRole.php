<?php

namespace App\Enums;

enum UserRole: int {
  case CUSTOMER = 0;
  case ADMIN = 99;
}