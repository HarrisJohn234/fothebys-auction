<?php

namespace App\Http\Requests\Admin;

class LotUpdateRequest extends LotStoreRequest
{
    // Inherits validation rules + prepareForValidation() from LotStoreRequest.
    // This keeps store/update rules consistent (including optional 'image').
}
