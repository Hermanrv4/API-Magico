<?php
return [
    'extra' => [
        'group' => 'extra',
        'clear' => [
            'url' => 'general/clear',
            'method' => 'get',
            'secure' => false,
            'action' => 'GeneralController@Clear',
        ],

        'migrate' => [
            'url' => 'general/migrate',
            'method' => 'get',
            'secure' => false,
            'action' => 'GeneralController@Migrate',
        ],
        'upload-photo' => [
            'url' => 'general/upload-photo',
            'method' => 'post',
            'secure' => false,
            'action' => 'GeneralController@UploadPhoto',
        ],
        'test-first' => [
            'url' => 'test/first/{val}',
            'method' => 'get',
            'secure' => false,
            'action' => 'TestController@First',
        ],
    ],
    'configuration' => [
        'group' => 'config',
        'need-update' => [
            'url' => 'need-update',
            'method' => 'post',
            'secure' => false,
            'action' => 'ConfigurationController@NeedUpdate',
        ],
        'get' => [
            'url' => 'get',
            'method' => 'post',
            'secure' => false,
            'action' => 'ConfigurationController@Get',
        ],
    ],
    'authentication' => [
        'group' => 'auth',
        'customer-email-register' => [
            'url' => 'customer-email-register',
            'method' => 'post',
            'secure' => false,
            'action' => 'AuthenticationController@CustomerEmailRegister',
        ],
        'customer-email-login' => [
            'url' => 'customer-email-login',
            'method' => 'post',
            'secure' => false,
            'action' => 'AuthenticationController@CustomerEmailLogin',
        ],
		'customer-change-password' => [
            'url' => 'customer-change-password',
            'method' => 'post',
            'secure' => false,
            'action' => 'AuthenticationController@CustomerUpdatePassword',
        ],
        'customer-facebook-auth' => [
            'url' => 'customer-facebook-auth',
            'method' => 'post',
            'secure' => false,
            'action' => 'AuthenticationController@CustomerFacebookAuth',
        ],

    ],
    'entity' => [
        'group' => 'entity',
        /*----------------------------------------*/
        'user-get' => [
            'url' => 'user/get',
            'method' => 'post',
            'secure' => false,
            'action' => 'UserController@Get',
        ],
        'user-send-by-email'=>[
            'url'=>'user/user-send-by-email',
            'method'=>'post',
            'secure'=>false,
            'action'=>'UserController@GetByEmail',
        ],
        'user-by-id' => [
            'url' => 'user/by-id',
            'method' => 'post',
            'secure' => false,
            'action' => 'UserController@GetById',
        ],
        'user-update-by-id' => [
            'url' => 'user/update-by-id',
            'method' => 'post',
            'secure' => false,
            'action' => 'UserController@UpdateData',
        ],
        
		'user-by-email' => [
            'url' => 'user/by-email',
            'method' => 'post',
            'secure' => false,
            'action' => 'UserController@GetByEmail',
        ],
        /*----------------------------------------*/
        'currency-get' => [
            'url' => 'currency/get',
            'method' => 'post',
            'secure' => false,
            'action' => 'CurrencyController@Get',
        ],
        'currency-get-by-id' => [
            'url' => 'currency/get-by-id',
            'method' => 'post',
            'secure' => false,
            'action' => 'CurrencyController@GetById',
        ],
        /*----------------------------------------*/
        'specification-get' => [
            'url' => 'specification/get',
            'method' => 'post',
            'secure' => false,
            'action' => 'SpecificationController@Get',
        ],
        'specification-by-code' => [
            'url' => 'specification/by-code',
            'method' => 'post',
            'secure' => false,
            'action' => 'SpecificationController@GetByCode',
        ],
        'specification-values-by-categories-and-specification' => [
            'url' => 'specification/values-by-categories-and-specification',
            'method' => 'post',
            'secure' => false,
            'action' => 'SpecificationController@GetValuesByCategoriesAndSpecificationId',
        ],
        'specification-values-by-ids' => [
            'url' => 'specification/values-by-ids',
            'method' => 'post',
            'secure' => false,
            'action' => 'SpecificationController@GetByIds',
        ],
        'specification-values-by-category' => [
            'url' => 'specification/specification-values-by-category',
            'method' => 'post',
            'secure' => false,
            'action' => 'SpecificationController@GetSpecificationValuesBySpecificationId',
        ],
        /*----------------------------------------*/
        'category-get' => [
            'url' => 'category/get',
            'method' => 'post',
            'secure' => false,
            'action' => 'CategoryController@Get',
        ],
        'category-by-urlcode' => [
            'url' => 'category/by-urlcode',
            'method' => 'post',
            'secure' => false,
            'action' => 'CategoryController@GetByUrlCode',
        ],
        'category-by-id' => [
            'url' => 'category/by-id',
            'method' => 'post',
            'secure' => false,
            'action' => 'CategoryController@GetById',
        ],
        'category-root-parents' => [
            'url' => 'category/root-parents',
            'method' => 'post',
            'secure' => false,
            'action' => 'CategoryController@GetRootParents',
        ],
        'category-root-parents-menu'=> [
            'url'=>'category-root/parents-menu',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CategoryController@GetRootParentsMenu'
        ],
        'category-childs-by-root-id' => [
            'url' => 'category/childs/by-root-id',
            'method' => 'post',
            'secure' => false,
            'action' => 'CategoryController@GetChildsByRoot',
        ],
        /*----------------------------------------*/
        'product-latest' => [
            'url' => 'product/latest',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetLatest',
        ],
		'product-facebook' => [
            'url' => 'product/product-facebook',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@ProductFacebook',
        ],
        'product-more-sell' => [
            'url' => 'product/more-sells',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetMoreSells',
        ],
        'product-proms' => [
            'url' => 'product/proms',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetProms',
        ],
        'product-similars' => [
            'url' => 'product/similars',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetSimilars',
        ],
        'product-spe-by-product-idspe' => [
            'url' => 'product/spe-by-product-idspe',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetFullSpecificationByProductIdSpeId',
        ],
        'product-allspe-by-product' => [
            'url' => 'product/spe-by-product-all',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetFullSpecificationByProduct',
        ],
        'product-most-similars' => [
            'url' => 'product/most-similars',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetMostSimilars',
        ],
        'product-by-id' => [
            'url' => 'product/by-id',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetById',
        ],
        'product-by-urlcode' => [
            'url' => 'product/by-urlcode',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetByUrlCode',
        ],
        'product-catalogue' => [
            'url' => 'product/catalogue',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetCatalogue',
        ],
        'product-catalogue-by-specifications-filter' => [
            'url' => 'product/catalogue-by-specifications-filter',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetProductsBySpecificationFilters',
        ],
        'product-url-by-specifications' => [
            'url' => 'product/url-by-specifications',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetUrlSpecifications',
        ],
        'product-get-filters' => [
            'url' => 'product/get-filters',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetFilters',
        ],
        'product-all' => [
            'url' => 'product/product-all',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductController@GetAll',
        ],
        /*----------------------------------------*/
        'product-price-get-by-product' => [
            'url' => 'product-price/get-by-product',
            'method' => 'post',
            'secure' => false,
            'action' => 'ProductPriceController@GetByProduct',
        ],
        /*----------------------------------------*/
        'cart-get' => [
            'url' => 'cart/get',
            'method' => 'post',
            'secure' => false,
            'action' => 'CartController@Get',
        ],
        'cart-add' => [
            'url' => 'cart/add',
            'method' => 'post',
            'secure' => false,
            'action' => 'CartController@Add',
        ],
        'cart-clear-for-user' => [
            'url' => 'cart/clear-for-user',
            'method' => 'post',
            'secure' => false,
            'action' => 'CartController@ClearForUser',
        ],
        'cart-clear-for-order'=> [
            'url'=>'cart/clear-for-order',
            'method'=> 'post',
            'secure'=> false,
            'action'=>'CartController@ClearForOrder',
        ],
        /*----------------------------------------*/
        'shipping-price-get' => [
            'url' => 'shipping-price/get',
            'method' => 'post',
            'secure' => false,
            'action' => 'ShippingPriceController@Get',
        ],
        /*----------------------------------------*/
        'ubication-get' => [
            'url' => 'ubication/get',
            'method' => 'post',
            'secure' => false,
            'action' => 'UbicationController@Get',
        ],
        'ubication-get-by-id' => [
            'url' => 'ubication/get-by-id',
            'method' => 'post',
            'secure' => false,
            'action' => 'UbicationController@GetById',
        ],
        'ubication-get-childs-full'=>[
            'url'=>'ubication/get-childs-full',
            'method'=>'post',
            'secure'=>false,
            'action'=>'UbicationController@GetFullChilds'
        ],
        /*----------------------------------------*/
        'order-get' => [
            'url' => 'order/get',
            'method' => 'post',
            'secure' => false,
            'action' => 'OrderController@Get',
        ],
        'order-get-by-token' => [
            'url' => 'order/get-by-token',
            'method' => 'post',
            'secure' => false,
            'action' => 'OrderController@GetByToken',
        ],
		'order-get-by-id' => [
            'url' => 'order/get-by-id',
            'method' => 'post',
            'secure' => false,
            'action' => 'OrderController@GetById',
        ],
        'order-update-payment-status' => [
            'url' => 'order/update-payment-status',
            'method' => 'post',
            'secure' => false,
            'action' => 'OrderController@UpdatePaymentStatus',
        ],
        'order-register' => [
            'url' => 'order/register',
            'method' => 'post',
            'secure' => false,
            'action' => 'OrderController@Register',
        ],
        'order-get-mail-data' => [
            'url' => 'order/get-mail-data',
            'method' => 'post',
            'secure' => false,
            'action' => 'OrderController@GetMailData',
        ],
        /*----------------------------------------*/
        'order-detail-get-by-order' => [
            'url' => 'order-detail/get-by-order',
            'method' => 'post',
            'secure' => false,
            'action' => 'OrderDetailController@GetByOrder',
        ],
		/*----------------------------------------*/
		'get-by-id' => [
		    'url' => 'qualification/get-by-id',
            'method' => 'post',
            'secure' => false,
            'action' => 'QualificationController@GetById',
		],
		'add-by-id' => [
		    'url' => 'qualification/add-by-id',
            'method' => 'post',
            'secure' => false,
            'action' => 'QualificationController@Add',
		],
		'get-by-product' => [
		    'url' => 'qualification/get-by-product',
            'method' => 'post',
            'secure' => false,
            'action' => 'QualificationController@Get',
		],
		'get-by-url-product' => [
		    'url' => 'qualification/get-by-url-product',
            'method' => 'post',
            'secure' => false,
            'action' => 'QualificationController@GetByUrl',
		],
		/*----------------------------------------*/
		
		'get-products-by-event' => [
		    'url' => 'events/get-products-by-event',
            'method' => 'post',
            'secure' => false,
            'action' => 'EventController@GetProductsByEvent',
		],
		'get-events-all' => [
		    'url' => 'events/get-events-all',
            'method' => 'post',
            'secure' => false,
            'action' => 'EventController@Get',
		],
		'get-events-id' => [
		    'url' => 'events/get-events-id',
            'method' => 'post',
            'secure' => false,
            'action' => 'EventController@GetByEventById',
		],
        'get-event-invitacion'=>[
            'url'=>'event/get-invitacion',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventInvitationController@Get'
        ],
        'event-register-invited'=>[
            'url'=>'event/register-invited',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventInvitationController@Register'
        ],
        'event-update'=>[
            'url'=>'event/update',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventController@UpdateEvent',
        ],
        'event-invitation-delete'=>[
            'url'=>'event-invitation/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventInvitationController@Delete',
        ],
        'event-products-get'=>[
            'url'=>'event-products/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventController@GetProductEvent'
        ],
		'get-events-token' => [
		    'url' => 'events/get-events-token',
            'method' => 'post',
            'secure' => false,
            'action' => 'EventController@GetByEventByToken',
		],
		'add-product-event' => [
		    'url' => 'events/add-product-event',
            'method' => 'post',
            'secure' => false,
            'action' => 'EventController@AddProduct',
		],
		'add-event' => [
		    'url' => 'events/add-event',
            'method' => 'post',
            'secure' => false,
            'action' => 'EventController@AddEvent',
		],
		'delete-event' => [
		    'url' => 'events/delete-event',
            'method' => 'post',
            'secure' => false,
            'action' => 'EventController@DeleteEvent',
		],
		/*----------------------------------------*/
		'get-address-by-user' => [
		    'url' => 'address/get-address-by-user',
            'method' => 'post',
            'secure' => false,
            'action' => 'AddressController@GetByUser',
		],
        'get-address-for-user' => [
		    'url' => 'address/get-address-for-user',
            'method' => 'post',
            'secure' => false,
            'action' => 'AddressController@GetForUser',
		],
		/*----------------------------------------*/
		'user-validate-facebook-email' => [
            'url' => 'user/user-validate-facebook-email',
            'method' => 'post',
            'secure' => false,
            'action' => 'UserController@GetUserByEmailAndOtherByFBID',
        ],
		/*----------------------------------------*/
		'add-contact' => [
            'url' => 'contact/add-contact',
            'method' => 'post',
            'secure' => false,
            'action' => 'ContactController@Add',
        ],
		'add-suscriber' => [
            'url' => 'suscriber/add-suscriber',
            'method' => 'post',
            'secure' => false,
            'action' => 'SuscriberController@Add',
        ],
        'product-complement'=>[
            'url'=>'product/complements',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductController@GetComplement'
        ],
        /*---------------------------------------*/
        'discount-get' => [
            'url' => 'discount/get',
            'method' => 'post',
            'secure' => false,
            'action' => 'DiscountController@GetAllAllowed',
        ],
        'discount-card-by-card' => [
            'url' => 'discount/get-by-card',
            'method' => 'post',
            'secure' => false,
            'action' => 'DiscountController@GetByCodeCard',
        ],
        'discount-validate' => [
            'url' => 'discount/validate',
            'method' => 'post',
            'secure' => false,
            'action' => 'DiscountController@GetByReference',
        ],        
        'discount-card-by-reference' => [
            'url' => 'discount/get-by-reference',
            'method' => 'post',
            'secure' => false,
            'action' => 'DiscountController@GetByCodeReference',
        ],
        'discount-use' => [
            'url' => 'discount/use',
            'method' => 'post',
            'secure' => false,
            'action' => 'DiscountController@PlusDiscount',
        ],
        /****************************************************** */
        'shops-list' => [
            'url' => 'shop/list',
            'method' => 'post',
            'secure' => false,
            'action' => 'ShopController@GetList',
        ],
        'shops-get' => [
            'url' => 'shop/get',
            'method' => 'post',
            'secure' => false,
            'action' => 'ShopController@GetById',
        ],
        /****************************************************** */
    ],
];
