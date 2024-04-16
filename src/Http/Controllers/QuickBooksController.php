<?php

namespace true9\QuickBooks\Http\Controllers;

use Illuminate\Http\Request;
use true9\QuickBooks\Credentials;
use true9\QuickBooks\QuickBooks;

class QuickBooksController extends Controller
{
    public function connect(QuickBooks $quickBooks)
    {
        return redirect($quickBooks->getAuthorizationUrl());
    }

    public function disconnect(Credentials $credentials)
    {
        $credentials->delete();

        return response()->noContent();
    }

    public function token(QuickBooks $quickBooks, Credentials $credentials, Request $request)
    {
        $token = $quickBooks->exchangeAuthorizationCode(
            $request->get('code'),
            $request->get('realmId')
        );

        $credentials->store($token);

        return redirect(config('quickbooks.redirect_url'));
    }

    public function status(Credentials $credentials, QuickBooks $quickBooks)
    {
        if ($credentials->exists() && ! $credentials->isExpired()) {
            return response([
                'connected' => true,
                'expires' => $credentials->getExpires(),
                'company_info' => $quickBooks->getDataService()->getCompanyInfo(),
            ]);
        }

        return response([
            'connected' => false,
        ]);
    }
}
