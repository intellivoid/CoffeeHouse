<?php


    namespace CoffeeHouse\Objects;

    /**
     * Class ApiPlan
     * @package CoffeeHouse\Objects
     */
    class ApiPlan
    {
        /**
         * The ID of the plan record
         *
         * @var int
         */
        public $Id;

        /**
         * Indicates if the plan is currently active or not
         *
         * @var bool
         */
        public $Active;

        /**
         * The Account ID that is associated with this plan
         *
         * @var bool
         */
        public $AccountId;

        /**
         * The ID of the Access Key that's associated with this plan
         *
         * @var int
         */
        public $AccessKeyId;

        /**
         * The plan type
         *
         * @var APIPlan|int
         */
        public $PlanType;

        /**
         * The promotion code that is used with this plan
         *
         * @var string
         */
        public $PromotionCode;

        /**
         * The amount of calls that this API Key is allowed to make
         *
         * 0 = Unlimited
         *
         * @var int
         */
        public $MonthlyCalls;

        /**
         * The amount to charge the user per billing cycle
         *
         * @var float
         */
        public $PricePerCycle;

        /**
         * The Unix Timestamp for the next billing cycle
         *
         * @var int
         */
        public $NextBillingCycle;

        /**
         * The interval for each billing cycle
         *
         * @var int
         */
        public $BillingCycle;

        /**
         * If this plan is active, this will disable access to the API Key until a payment
         * has been made
         *
         * @var bool
         */
        public $PaymentRequired;

        /**
         * The Unix Timestamp of when this record was created
         *
         * @var int
         */
        public $PlanCreated;

        /**
         * Indicates if this plan has started or not
         *
         * @var bool
         */
        public $PlanStarted;

        /**
         * Plan constructor.
         */
        public function __construct()
        {
            $this->Id = 0;
            $this->Active = false;
            $this->AccountId = 0;
            $this->AccessKeyId = 0;
            $this->PlanType = 0;
            $this->PromotionCode = 'None';
            $this->MonthlyCalls = 0;
            $this->PricePerCycle = 0;
            $this->BillingCycle = 0;
            $this->NextBillingCycle = 0;
            $this->PaymentRequired = false;
            $this->PlanCreated = false;
            $this->PlanStarted = 0;
        }

        /**
         * Converts this object into an array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => (int)$this->Id,
                'active' => (bool)$this->Active,
                'account_id' => (int)$this->AccountId,
                'access_key_id' => (int)$this->AccessKeyId,
                'plan_type' => (int)$this->PlanType,
                'promotion_code' => $this->PromotionCode,
                'monthly_calls' => (int)$this->MonthlyCalls,
                'price_per_cycle' => (float)$this->PricePerCycle,
                'billing_cycle' => (int)$this->BillingCycle,
                'payment_required' => (bool)$this->PaymentRequired,
                'plan_created' => (int)$this->PlanCreated,
                'plan_started' => (bool)$this->PlanStarted
            );
        }

        /**
         * @param array $data
         * @return ApiPlan
         */
        public static function fromArray(array $data): ApiPlan
        {
            $ApiPlanObject = new ApiPlan();

            if(isset($data['id']))
            {
                $ApiPlanObject->Id = (int)$data['id'];
            }

            if(isset($data['active']))
            {
                $ApiPlanObject->Active = (bool)$data['active'];
            }

            if(isset($data['account_id']))
            {
                $ApiPlanObject->AccountId = (int)$data['account_id'];
            }

            if(isset($data['access_key_id']))
            {
                $ApiPlanObject->AccessKeyId = (int)$data['access_key_id'];
            }

            if(isset($data['plan_type']))
            {
                $ApiPlanObject->PlanType = (int)$data['plan_type'];
            }

            if(isset($data['promotion_code']))
            {
                $ApiPlanObject->PromotionCode = $data['promotion_code'];
            }

            if(isset($data['monthly_calls']))
            {
                $ApiPlanObject->MonthlyCalls = (int)$data['monthly_calls'];
            }

            if(isset($data['price_per_cycle']))
            {
                $ApiPlanObject->PricePerCycle = (float)$data['price_per_cycle'];
            }

            if(Isset($data['next_billing_cycle']))
            {
                $ApiPlanObject->NextBillingCycle = (int)$data['next_billing_cycle'];
            }

            if(isset($data['billing_cycle']))
            {
                $ApiPlanObject->BillingCycle = (int)$data['billing_cycle'];
            }

            if(isset($data['payment_required']))
            {
                $ApiPlanObject->PaymentRequired = (bool)$data['payment_required'];
            }

            if(isset($data['plan_created']))
            {
                $ApiPlanObject->PlanCreated = (int)$data['plan_created'];
            }

            if(isset($data['plan_started']))
            {
                $ApiPlanObject->PlanStarted = (bool)$data['plan_started'];
            }

            return $ApiPlanObject;
        }

}