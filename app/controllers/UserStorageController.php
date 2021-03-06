<?php

    use TestOauthApp\Command\FilePurchase\TransactionNotApprovedException;
    use TestOauthApp\Command\StoragePurchase\BuyStorageSpaceViaCreditCardCommand;

    class UserStorageController extends \BaseController
    {

        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function index()
        {
            $active = 'storage';
            $title = "storage";
            $storageSpaces = SellableStorageSpace::orderBy('size')->get();

            return View::make('storage.index', compact('storageSpaces', 'active', 'title'));
        }

        public function buyFailure($data)
        {
            $response = Input::get('xmlmsg');

            try {
                $this->execute(BuyStorageSpaceViaCreditCardCommand::class,
                    [
                        'creditCardTransactionResponse' => $response,
                        'storageSpaceData'              => $data
                    ]);
            } catch (TransactionNotApprovedException $ex) {
                return Redirect::route('user.storage')->withStatus('Failed to Buy Storage');
            }

            return Redirect::home();
        }

        public function buySuccess($data)
        {
            $response = Input::get('xmlmsg');

            try {
                $this->execute(BuyStorageSpaceViaCreditCardCommand::class,
                    [
                        'creditCardTransactionResponse' => $response,
                        'storageSpaceData'              => $data
                    ]);
            } catch (TransactionNotApprovedException $ex) {
                return Redirect::route('user.storage')->withError('Failed to Buy Storage');
            }

            return Redirect::route('user.storage')->withStatus('Successfully Bought');
        }
    }
