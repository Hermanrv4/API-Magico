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
    ],
    'authentication' => [
        'group' => 'authentication',
        'email-login' => [
            'url' => 'email-login',
            'method' => 'post',
            'secure' => false,
            'action' => 'AuthenticationController@EmailLogin',
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
        'parameters' => [
            'url' => 'parameters',
            'method' => 'post',
            'secure' => false,
            'action' => 'ConfigurationController@GetParameters',
        ],
        'parameters-get' => [
            'url' => 'parameters/get/name',
            'method' => 'post',
            'secure' => false,
            'action' => 'ConfigurationController@GetParameterByName',
        ],
        'parameters-save' => [
            'url' => 'parameters/save',
            'method' => 'post',
            'secure' => false,
            'action' => 'ConfigurationController@SaveParameters',
        ],
        'parameters-update-value-by-code' => [
            'url' => 'parameters/update-value-by-code',
            'method' => 'post',
            'secure' => false,
            'action' => 'ConfigurationController@UpdateValueByCode',
        ],
    ],
    'entity'=>[
        'group'=>'entity',
        'address-get'=>[
            'url'=>'address/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'AddressController@Get',
        ],
        'address-register'=>[
            'url'=>'address/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'AddressController@Register',
        ],
        'address-delete'=>[
            'url'=>'address/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'AddressController@Delete',
        ],
        'category-get'=>[
            'url'=>'category/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CategoryController@Get',
        ],
        'category-root-get'=>[
            'url'=>'category-root/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CategoryController@GetByRoot'
        ],
        'category-childs-by-root-id-get'=>[
            'url'=>'category/childs/by-root-id',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CategoryController@GetChildsByRoot'
        ],
        'category-register'=>[
            'url'=>'category/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CategoryController@Register',
        ],
        'category-delete'=>[
            'url'=>'category/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CategoryController@Delete',
        ],
        'category-specification-get'=>[
            'url'=>'category-specification/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CategorySpecificationController@Get',
        ],
        'category-specification-register'=>[
            'url'=>'category-specification/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CategorySpecificationController@Register',
        ],
        'category-specification-delete'=>[
            'url'=>'category-specification/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CategorySpecificationController@Delete',
        ],
        'contact-get'=>[
            'url'=>'contact/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ContactController@Get',
        ],
        'contact-register'=>[
            'url'=>'contact/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ContactController@Register',
        ],
        'contact-delete'=>[
            'url'=>'contact/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ContactController@Delete',
        ],
        'currency-get'=>[
            'url'=>'currency/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CurrencyController@Get',
        ],        
        'currency-register'=>[
            'url'=>'currency/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CurrencyController@Register',
        ],
        'currency-delete'=>[
            'url'=>'currency/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CurrencyController@Delete',
        ],
        'event-get'=>[
            'url'=>'event/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventController@Get',
        ],
        'event-register'=>[
            'url'=>'event/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventController@Register',
        ],
        'event-delete'=>[
            'url'=>'event/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventController@Delete',
        ],
        'event-date'=>[
            'url'=>'event/date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventController@GetDateEvents',
        ],
        'event-no-sent-reminder'=>[
            'url'=>'event/get-no-sent-reminder',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventController@GetNoSentReminderWithGuests',
        ],
        'event-update-sent-reminder-success'=>[
            'url'=>'event/update-sent-reminder-success',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventController@UpdateSentReminderSuccess',
        ],
        'event-invitation-get'=>[
            'url'=>'event-invitation/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventInvitationController@Get',
        ],
        'event-invitation-register'=>[
            'url'=>'event-invitation/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventInvitationController@Register',
        ],
        'event-invitation-delete'=>[
            'url'=>'event-invitation/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'EventInvitationController@Delete',
        ],
        'list-products-event-get'=>[
            'url'=>'list-products-event/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ListProducstEventController@Get',
        ],
        'list-products-event-register'=>[
            'url'=>'list-products-event/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ListProducstEventController@Register',
        ],
        'list-products-event-delete'=>[
            'url'=>'list-products-event/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ListProducstEventController@Delete',
        ],
        'order-get'=>[
            'url'=>'order/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'OrderController@Get',
        ],
        'order-change-status'=>[
            'url'=>'order/change-status',
            'method'=>'post',
            'secure'=>false,
            'action'=>'OrderController@ChangeStatus',
        ],
        'order-detail-get'=>[
            'url'=>'order-detail/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'OrderDetailController@Get',
        ],
        'order-detail-register'=>[
            'url'=>'order-detail/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'OrderDetailController@Register',
        ],
        'order-detail-delete'=>[
            'url'=>'order-detail/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'OrderDetailController@Delete',
        ],
        'parameter-get'=>[
            'url'=>'parameter/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ParameterController@GetById',
        ],
        'parameter-get-codes'=>[
            'url'=>'parameter/get-codes',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ParameterController@GetCodes',
        ],
        'parameter-get-slide-landing'=>[
            'url'=>'parameter/get-slide-landing',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ParameterController@GetCodeSlideLanding',
        ],
        'parameter-get-codes-all'=>[
            'url'=>'parameter/get-codes-all',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ParameterController@GetCodesOfValues',
        ],
        'parameter-register'=>[
            'url'=>'parameter/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ParameterController@Register',
        ],
        'product-get'=>[
            'url'=>'product/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductController@Get',
        ],
        'product-change-status'=>[
            'url'=>'product/change-status',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductController@ChangeStatus',
        ],
        'product-register'=>[
            'url'=>'product/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductController@Register',
        ],
        'product-delete'=>[
            'url'=>'product/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductController@Delete',
        ],
        'product-group-get'=>[
            'url'=>'product-group/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductGroupController@Get',
        ],
        'product-group-register'=>[
            'url'=>'product-group/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductGroupController@Register',
        ],
        'product-group-delete'=>[
            'url'=>'product-group/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductGroupController@Delete',
        ],
        'product-price-get'=>[
            'url'=>'product-price/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductPriceController@Get',
        ],
        'product-price-register'=>[
            'url'=>'product-price/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductPriceController@Register',
        ],
        'product-price-delete'=>[
            'url'=>'product-price/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductPriceController@Delete',
        ],
        'product-specification-get'=>[
            'url'=>'product-specification/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductSpecificationController@Get',
        ],
        'product-specification-register'=>[
            'url'=>'product-specification/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductSpecificationController@Register',
        ],
        'product-specification-delete'=>[
            'url'=>'product-specification/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductSpecificationController@Delete',
        ],
        'shipping-price-get'=>[
            'url'=>'shipping-price/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ShippingPriceController@Get',
        ],
        'shipping-price-register'=>[
            'url'=>'shipping-price/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ShippingPriceController@Register',
        ],
        'shipping-price-update'=>[
            'url'=>'shipping-price/update-full-ubications',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ShippingPriceController@UpdateFullUbications',
        ],
        'shipping-price-delete'=>[
            'url'=>'shipping-price/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ShippingPriceController@Delete',
        ],
        'specification-get'=>[
            'url'=>'specification/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'SpecificationController@Get',
        ],
        'specification-register'=>[
            'url'=>'specification/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'SpecificationController@Register',
        ],
        'specification-delete'=>[
            'url'=>'specification/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'SpecificationController@Delete',
        ],
        'suscriber-get'=>[
            'url'=>'suscriber/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'SuscriberController@Get'
        ],
        'suscriber-register'=>[
            'url'=>'suscriber/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'SuscriberController@Register',
        ],
        'suscriber-delete'=>[
            'url'=>'suscriber/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'SuscriberController@Delete',
        ],
        'type-get'=>[
            'url'=>'type/get',
            'method'=>'post',
            'secure'=>false,
            'action'=> 'TypeController@Get',
        ],
        'type-register'=>[
            'url'=>'type/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TypeController@Register',
        ],
        'type-delete'=>[
            'url'=>'type/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TypeController@Delete',
        ],

        'type-group-get'=>[
            'url'=>'type-group/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TypeGroupController@Get',
        ],
        'ubication-get'=>[
            'url'=>'ubication/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'UbicationController@Get',
        ],
        'ubication-root-get'=>[
            'url'=>'ubication-root/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'UbicationController@GetByRoot'
        ],
        'ubication-register'=>[
            'url'=>'ubication/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'UbicationController@Register'
        ],
        'ubication-delete'=>[
            'url'=>'ubication/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'UbicationController@Delete',
        ],
        'user-get'=>[
            'url'=>'user/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'UserController@Get',
        ],
        'user-register'=>[
            'url'=>'user/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'UserController@Register',
        ],
        'user-delete'=>[
            'url'=>'user/delete',
            'method'=>'post',
            'secure'=>false,
            'action'=>'UserController@Delete',
        ],
        'order-product-date-get'=>[
            'url'=>'order/get-product-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'OrderController@GetByProductAndDate',
        ],
        'order-customer-date-get'=>[
            'url'=>'order/get-customer-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'OrderController@GetCustomerByDateRange',
        ],
        'order-billed-get'=>[
            'url'=>'order-billed/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'OrderController@GetAllOrderBilled',
        ],
        'order-billed-get-filter'=>[
            'url'=>'order-billed/get-filters',
            'method'=>'post',
            'secure'=>false,
            'action'=>'OrderController@GetByFilters',
        ],
        'electronic-billing-sale-get'=>[
            'url'=>'electronic-billing-sale/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ElectronicBillingSaleController@Get',
        ],
        'electronic-billing-sale-exists-order'=>[
            'url'=>'electronic-billing-sale/exists-order',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ElectronicBillingSaleController@ExistOrder',
        ],
        'electronic-billing-sale-register'=>[
            'url'=>'electronic-billing-sale/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ElectronicBillingSaleController@Register',
        ],
        'electronic-billing-sale-voided'=>[
            'url'=>'electronic-billing-sale/voided',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ElectronicBillingSaleController@Voided',
        ],
        'electronic-billing-get-correlative'=>[
            'url'=>'electronic-billing/get-correlative',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ElectronicBillingController@GetCorrelative',
        ],
        'electronic-billing-update'=>[
            'url'=>'electronic-billing/update-correlative',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ElectronicBillingController@UpdateCorrelative',
        ],
        'claim-book-get'=>[
            'url'=>'claim-book/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ClaimBookController@Get',
        ],
        'claim-book-register'=>[
            'url'=>'claim-book/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ClaimBookController@Register',
        ],
        'ld-product-register'=>[
            'url'=>'ld-product/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'LdProductController@Register',
        ],
        'ld-product-get'=>[
            'url'=>'ld-product/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'LdProductController@Get',
        ],
        'ld-category-register'=>[
            'url'=>'ld-category/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'LdCategoryController@Register',
        ],
        'ld-category-get'=>[
            'url'=>'ld-category/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'LdCategoryController@Get',
        ],
        'ld-product-procedure'=>[
            'url'=>'ld-product/procedure',
            'method'=>'post',
            'secure'=>false,
            'action'=>'LdProductController@ExeProcedure',
        ],
        'ld-billing-categorie-sale'=>[
            'url'=>'ld-billing/categorie-sale',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ElectronicBillingSaleController@GetSaleCategoriesDate',
        ],
        'product-get-order-sale'=>[
            'url'=>'product-get/order-sale',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductController@GetProductOrderSaleDate',
        ],
        'product-group-get-order-sale'=>[
            'url'=>'product-group-get/order-sale',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductGroupController@ProductGroupGetOrderSale',
        ],
        'order-get-status-date'=>[
            'url'=>'order-get/status-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'OrderController@GetOrderStatusDate',
        ],
        'suscriber-get-date'=>[
            'url'=>'suscriber-get/date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'SuscriberController@GetSuscriberDate',
        ],
        'order-get-status-of-date'=>[
            'url'=>'order-get/status-of-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'OrderController@GetOrderStatusOfDate',
        ],
        'user-get-register-of-date'=>[
            'url'=>'user-get/register-of-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'UserController@GetUserOfDateRegister',
        ],
        'categories-get-data-billing-date'=>[
            'url'=>'categories-get/data-billing-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CategoryController@GetCategoriesOrderBilling'
        ],
        'product-get-data-billing-of-date'=>[
            'url'=>'product-get/data-billing-of-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductController@GetProductBillingSaleOfDate'
        ],
        'product-group-get-data-billing-of-date'=>[
            'url'=>'product-group-get/data-billing-of-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'ProductGroupController@ProductGroupGetBillingSaleOfDate'
        ],
        'user-get-order-of-date'=>[
            'url'=>'user-get/order-of-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'UserController@GetOrderForUserOfDate'
        ],
        'user-get-billing-of-date'=>[
            'url'=>'user-get/billing-of-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'UserController@GetBillingForUserOfDate'
        ],
        'order-get-of-date'=>[
            'url'=>'order-get/of-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'OrderController@GetOrderOfDate'
        ],
        'tracing-get'=>[
            'url'=>'tracing/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@Get',
        ],
        'tracing-register'=>[
            'url'=>'tracing/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@Register',
        ],
        'tracing-get-data-or-date'=>[
            'url'=>'tracing-get/data-or-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@GetVisitPage'
        ],
        'tracing-get-preview-or-date'=>[
            'url'=>'tracing-get/preview-or-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@GetPreviewProduct'
        ],
        'tracing-get-addCard-or-date'=>[
            'url'=>'tracing-get/addCard-or-date',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@GetAddCardProduct',
        ],
        'tracing-get-visit-page-usernull'=>[
            'url'=>'tracing-get/visit-page-usernull',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@GetCountVisitUserIsNullOfDate'
        ],
        'tracing-get-category-visit-usernull'=>[
            'url'=>'tracing-get/category-visit-usernull',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@GetCategoryVisitUserNull'
        ],
        'tracing-get-visit-category'=>[
            'url'=>'tracing-get/visit-category',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@GetCategoryVisit'
        ],
        'tracing-get-visit-page-for-user'=>[
            'url'=>'tracings-get/visit-page-for-user',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@GetVisitPageOfUser'
        ],
        'tracing-get-preview-for-user'=>[
            'url'=>'tracing-get/preview-for-user',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@GetPreviewForUser'
        ],
        'tracing-get-addcard-for-user'=>[
            'url'=>'tracing-get/addcard-for-user',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@GetAddCardForUser'
        ],
        'tracing-get-category-visit-for-user'=>[
            'url'=>'tracing-get/category-visit-for-user',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@GetCategoryOfUser'
        ],
        'tracing-get-count-wsp'=>[
            'url'=>'tracing-get/count-wsp',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@GetCountWsp'
        ],
        'tracing-get-count-messenger'=>[
            'url'=>'tracing-get/count-messenger',
            'method'=>'post',
            'secure'=>false,
            'action'=>'TracingController@GetCountMessenger'
        ],
        'discount-get'=>[
            'url'=>'discount/get',
            'method'=>'post',
            'secure'=>false,
            'action'=>'DiscountController@GetDiscount',
        ],
        'discount-register'=>[
            'url'=>'discount/register',
            'method'=>'post',
            'secure'=>false,
            'action'=>'DiscountController@RegisterDiscount',
        ],
        'get-discount-affectation-code'=>[
            'url'=>'get-discount/affectation-code',
            'method'=>'post',
            'secure'=>false,
            'action'=>'DiscountController@GetAffectation',
        ],
        'discount-save-affectation'=>[
            'url'=>'discount/save-affectation',
            'method'=>'post',
            'secure'=>false,
            'action'=>'DiscountController@saveAffectation',
        ],
        'Specification-change-filter'=>[
            'url'=>'specification/change/filter',
            'method'=>'post',
            'secure'=>false,
            'action'=>'SpecificationController@ChangeSpecifications'
        ],
        'specification-filter-order'=>[
            'url'=>'specification/filter/order',
            'method'=>'post',
            'secure'=>false,
            'action'=>'SpecificationController@OrderFilterSpecification',
        ],
        'carts-get-by-last-update'=>[
            'url'=>'carts/get-by-last-update',
            'method'=>'post',
            'secure'=>false,
            'action'=>'CartController@GetCartsByLastUpdate',
        ],
    ],
];
