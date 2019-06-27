<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\Abstracts\PlanSearchMethod;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\ApiPlanNotFoundException;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\InvalidApiPlanTypeException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Objects\ApiPlan;
    use Exception;
    use ModularAPI\Abstracts\AccessKeySearchMethod;
    use ModularAPI\Abstracts\UsageType;
    use ModularAPI\Configurations\UsageConfiguration;
    use ModularAPI\Exceptions\AccessKeyNotFoundException;
    use ModularAPI\Exceptions\InvalidAccessKeyStatusException;
    use ModularAPI\Exceptions\NoResultsFoundException;
    use ModularAPI\Exceptions\UnsupportedSearchMethodException;
    use ModularAPI\ModularAPI;
    use ModularAPI\Objects\AccessKey;
    use ModularAPI\Utilities\Builder;
    use ModularAPI\Utilities\Hashing;

    /**
     * Class ApiPlanManager
     * @package CoffeeHouse\Managers
     */
    class ApiPlanManager
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;
        /**
         * @var ModularAPI
         */
        private $modularApi;

        /**
         * ApiPlanManager constructor.
         * @param CoffeeHouse $coffeeHouse
         * @throws Exception
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
            $this->modularApi = new ModularAPI();
        }

        /**
         * Creates a new plan in the database
         *
         * @param ApiPlan $apiPlan
         * @return ApiPlan
         * @throws ApiPlanNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function createPlan(ApiPlan $apiPlan): ApiPlan
        {
            // Register the plan into the database
            $accessKeyId = (int)$apiPlan->AccessKeyId;
            $accountId = (int)$apiPlan->AccountId;
            $active = (int)$apiPlan->Active;
            $billing_cycle = (int)$apiPlan->BillingCycle;
            $monthly_calls = (int)$apiPlan->MonthlyCalls;
            $next_billing_cycle = (int)$apiPlan->NextBillingCycle;
            $payment_required = (int)$apiPlan->PaymentRequired;
            $plan_created = (int)$apiPlan->PlanCreated;
            $plan_started = (int)$apiPlan->PlanStarted;
            $plan_type = (int)$apiPlan->PlanType;
            $price_per_cycle = (float)$apiPlan->PricePerCycle;
            $promotion_code = $this->coffeeHouse->getDatabase()->real_escape_string($apiPlan->PromotionCode);

            $query = sprintf(
                "INSERT INTO `plans` (active, account_id, access_key_id, plan_type, promotion_code, monthly_calls, price_per_cycle, next_billing_cycle, billing_cycle, payment_required, plan_created, plan_started) VALUES (%s, %s, %s, %s, '%s', %s, %s, %s, %s, %s, %s, %s)",
                $active, $accountId, $accessKeyId, $plan_type, $promotion_code, $monthly_calls, $price_per_cycle, $next_billing_cycle, $billing_cycle, $payment_required, $plan_created, $plan_started
            );
            $query_results = $this->coffeeHouse->getDatabase()->query($query);

            if($query_results == true)
            {
                return $this->getPlan(PlanSearchMethod::byAccessKeyId, $accessKeyId);
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }

        /**
         * Gets an existing plan from the database
         *
         * @param string $search_method
         * @param string $value
         * @return ApiPlan
         * @throws ApiPlanNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function getPlan(string $search_method, string $value): ApiPlan
        {
            switch($search_method)
            {
                case PlanSearchMethod::byId:
                    $search_method = $this->coffeeHouse->getDatabase()->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case PlanSearchMethod::byAccessKeyId:
                    $search_method = $this->coffeeHouse->getDatabase()->real_escape_string($search_method);
                    $value = (int)$this->coffeeHouse->getDatabase()->real_escape_string($value);
                    break;

                case PlanSearchMethod::byAccountId:
                    $search_method = $this->coffeeHouse->getDatabase()->real_escape_string($search_method);
                    $value = (int)$this->coffeeHouse->getDatabase()->real_escape_string($value);
                    break;

                default:

                    throw new InvalidSearchMethodException();
            }

            $Query = "SELECT id, active, account_id, access_key_id, plan_type, promotion_code, monthly_calls, price_per_cycle, next_billing_cycle, billing_cycle, payment_required, plan_created, plan_started FROM `plans` WHERE $search_method=$value";
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
            else
            {
                if ($QueryResults->num_rows !== 1)
                {
                    throw new ApiPlanNotFoundException();
                }

                return ApiPlan::fromArray($QueryResults->fetch_array(MYSQLI_ASSOC));
            }
        }

        /**
         * Updates an existing plan in the database
         *
         * @param ApiPlan $apiPlan
         * @return bool
         * @throws ApiPlanNotFoundException
         * @throws DatabaseException
         * @throws AccessKeyNotFoundException
         * @throws NoResultsFoundException
         * @throws UnsupportedSearchMethodException
         */
        function updatePlan(ApiPlan $apiPlan): bool
        {
            if($this->IdExists($apiPlan->Id) == false)
            {
                throw new ApiPlanNotFoundException();
            }

            $id = (int)$apiPlan->Id;
            $active = (int)$apiPlan->Active;
            $account_id = (int)$apiPlan->AccountId;
            $access_key_id = (int)$apiPlan->AccessKeyId;
            $plan_type = (int)$apiPlan->PlanType;
            $promotion_code = $this->coffeeHouse->getDatabase()->real_escape_string($apiPlan->PromotionCode);
            $monthly_calls = (int)$apiPlan->MonthlyCalls;
            $price_per_cycle = (float)$apiPlan->PricePerCycle;
            $next_billing_cycle = (int)$apiPlan->NextBillingCycle;
            $billing_cycle = (int)$apiPlan->BillingCycle;
            $payment_required = (int)$apiPlan->PaymentRequired;
            $plan_started = (int)$apiPlan->PlanStarted;

            $Query = "UPDATE `plans` SET active=$active, account_id=$account_id, access_key_id=$access_key_id, plan_type=$plan_type, promotion_code='$promotion_code', monthly_calls=$monthly_calls, price_per_cycle=$price_per_cycle, next_billing_cycle=$next_billing_cycle, billing_cycle=$billing_cycle, payment_required=$payment_required, plan_started=$plan_started WHERE id=$id";
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }

            $AccessKeyObject = $this->modularApi->AccessKeys()->Manager->get(AccessKeySearchMethod::byID, $apiPlan->AccessKeyId);

            if($apiPlan->MonthlyCalls == 0)
            {
                $AccessKeyObject->Usage->UsageType = UsageType::Unlimited;
                $AccessKeyObject->Usage->ResetInterval = 0;
                $AccessKeyObject->Usage->NextInterval = 0;
                $AccessKeyObject->Usage->Limit = 0;
            }
            else
            {
                $AccessKeyObject->Usage->UsageType = UsageType::DateIntervalLimit;
                $AccessKeyObject->Usage->ResetInterval = 2628002;
                $AccessKeyObject->Usage->NextInterval = 0;
                $AccessKeyObject->Usage->Limit = $apiPlan->MonthlyCalls;
            }

            $this->modularApi->AccessKeys()->Manager->update($AccessKeyObject);

            return true;
        }

        /**
         * Determines if the plan ID Record exists in the database or not
         *
         * @param int $id
         * @return bool
         */
        function IdExists(int $id): bool
        {
            try
            {
                $this->getPlan(PlanSearchMethod::byId, $id);
                return true;
            }
            catch(Exception $exception)
            {
                return false;
            }
        }

        /**
         * Determines if the Access Key ID is associated with any plan
         *
         * @param int $accessKeyId
         * @return bool
         */
        function accessKeyIdExists(int $accessKeyId): bool
        {
            try
            {
                $this->getPlan(PlanSearchMethod::byAccessKeyId, $accessKeyId);
                return true;
            }
            catch(Exception $exception)
            {
                return false;
            }
        }

        /**
         * Determines if the Account ID if the associated with any plan
         *
         * @param int $accountId
         * @return bool
         */
        function accountIdExists(int $accountId): bool
        {
            try
            {
                $this->getPlan(PlanSearchMethod::byAccountId, $accountId);
                return true;
            }
            catch(Exception $exception)
            {
                return false;
            }
        }

        /**
         * @param ApiPlan $apiPlan
         * @return AccessKey
         * @throws NoResultsFoundException
         * @throws UnsupportedSearchMethodException
         * @throws AccessKeyNotFoundException
         */
        function updateSignatures(ApiPlan $apiPlan): AccessKey
        {
            $AccessKeyObject = $this->modularApi->AccessKeys()->Manager->get(AccessKeySearchMethod::byID, $apiPlan->AccessKeyId);

            $CurrentTime = time();

            $AccessKeyObject->Signatures->PrivateSignature = Hashing::generatePrivateSignature(
                $AccessKeyObject->Signatures->TimeSignature,
                $AccessKeyObject->Signatures->IssuerName,
                $CurrentTime
            );

            $AccessKeyObject->Signatures->PublicSignature = Hashing::generatePublicSignature(
                $AccessKeyObject->Signatures->TimeSignature,
                $AccessKeyObject->Signatures->PrivateSignature
            );

            $AccessKeyObject->PublicKey = Hashing::calculatePublicKey($AccessKeyObject->Signatures->createCertificate());
            $this->modularApi->AccessKeys()->Manager->update($AccessKeyObject);

            return $AccessKeyObject;
        }

        /**
         * Starts a plan with an existing account
         *
         * @param int $accountId
         * @param string $planType
         * @param int $monthlyCalls
         * @param int $billingCycle
         * @param float $price
         * @param string $promotion_code
         * @return ApiPlan
         * @throws AccessKeyNotFoundException
         * @throws ApiPlanNotFoundException
         * @throws DatabaseException
         * @throws InvalidApiPlanTypeException
         * @throws InvalidSearchMethodException
         * @throws NoResultsFoundException
         * @throws UnsupportedSearchMethodException
         * @throws InvalidAccessKeyStatusException
         */
        function startPlan(int $accountId, string $planType, int $monthlyCalls, int $billingCycle, float $price, string $promotion_code = 'NORMAL'): ApiPlan
        {
            $PlanExists = false;
            $Plan = new ApiPlan();

            // If the plan exists, load it from the database
            if($this->accountIdExists($accountId) == true)
            {
                $Plan = $this->getPlan(PlanSearchMethod::byAccountId, $accountId);
                $PlanExists = true;
            }
            else
            {
                $Plan->AccountId = $accountId;
            }

            $current_time = time();

            // Set the properties
            $Plan->BillingCycle = $billingCycle;
            $Plan->PricePerCycle = $price;
            $Plan->MonthlyCalls = $monthlyCalls;
            $Plan->PromotionCode = $promotion_code;
            $Plan->Active = true;
            $Plan->PlanStarted = true;
            $Plan->PaymentRequired = false;
            $Plan->NextBillingCycle = $current_time + $Plan->BillingCycle;
            $Plan->PlanCreated = $current_time;
            $Plan->AccountId = $accountId;

            // Set the plan type
            switch($planType)
            {
                case \CoffeeHouse\Abstracts\APIPlan::Free:
                    $Plan->PlanType = \CoffeeHouse\Abstracts\APIPlan::Free;
                    break;

                case \CoffeeHouse\Abstracts\APIPlan::Basic:
                    $Plan->PlanType = \CoffeeHouse\Abstracts\APIPlan::Basic;
                    break;

                case \CoffeeHouse\Abstracts\APIPlan::Enterprise:
                    $Plan->PlanType = \CoffeeHouse\Abstracts\APIPlan::Enterprise;
                    break;

                default:
                    throw new InvalidApiPlanTypeException();
            }

            $AccessKey = null;

            // If the plan didn't exist, create a new access key
            if($PlanExists == false)
            {
                // If monthly calls is set to 0, create an unlimited key
                if($Plan->MonthlyCalls == 0)
                {
                    $AccessKey = $this->modularApi->AccessKeys()->createKey(
                        UsageConfiguration::unlimited(),
                        array(
                            'type' => 'allow_all_permissions'
                        )
                    );
                }
                // Else, create a normal key that resets each month
                else
                {
                    $AccessKey = $this->modularApi->AccessKeys()->createKey(
                        UsageConfiguration::dateIntervalLimit($Plan->MonthlyCalls, 2628002),
                        array(
                            'type' => 'allow_all_permissions'
                        )
                    );
                }

                $Plan->AccessKeyId = $AccessKey->ID;
            }
            else
            {
                $AccessKey = $this->modularApi->AccessKeys()->Manager->get(AccessKeySearchMethod::byID, $Plan->AccessKeyId);

                $AccessKey->Analytics->LastMonthAvailable = false;
                $AccessKey->Analytics->LastMonthID = null;
                $AccessKey->Analytics->LastMonthUsage = [];

                $AccessKey->Analytics->CurrentMonthAvailable = true;
                $AccessKey->Analytics->CurrentMonthID = Hashing::calculateMonthID((int)date('n'), (int)date('Y'));
                $AccessKey->Analytics->CurrentMonthUsage = Builder::createMonthArray();

                if($Plan->MonthlyCalls == 0)
                {
                    $AccessKey->Usage->loadConfiguration(
                        UsageConfiguration::unlimited()
                    );
                }
                else
                {
                    $AccessKey->Usage->loadConfiguration(
                        UsageConfiguration::dateIntervalLimit($Plan->MonthlyCalls, 2628002)
                    );
                }

                $this->modularApi->AccessKeys()->Manager->update($AccessKey);
            }

            $Plan->NextBillingCycle = time() + $Plan->BillingCycle;

            if($PlanExists == true)
            {
                $this->updatePlan($Plan);
                $this->updateSignatures($Plan);
                return $Plan;
            }

            return $this->createPlan($Plan);
        }

        /**
         * Cancels an existing plan with an account
         *
         * @param int $account_id
         * @return bool
         * @throws AccessKeyNotFoundException
         * @throws ApiPlanNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws NoResultsFoundException
         * @throws UnsupportedSearchMethodException
         */
        function cancelPlan(int $account_id): bool
        {
            if($this->accountIdExists($account_id) == false)
            {
                return false;
            }

            $Plan = $this->getPlan(PlanSearchMethod::byAccountId, $account_id);

            $Plan->Active = false;
            $Plan->PlanStarted = false;
            $Plan->NextBillingCycle = 0;

            $this->updatePlan($Plan);

            return true;
        }
    }