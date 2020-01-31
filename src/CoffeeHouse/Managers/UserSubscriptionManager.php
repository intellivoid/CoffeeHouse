<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\Abstracts\UserSubscriptionSearchMethod;
    use CoffeeHouse\Abstracts\UserSubscriptionStatus;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Objects\UserSubscription;
    use msqg\QueryBuilder;

    /**
     * Class UserSubscriptionManager
     * @package CoffeeHouse\Managers
     */
    class UserSubscriptionManager
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * UserSubscriptionManager constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * Registers a UserSubscription into the database
         *
         * @param int $account_id
         * @param int $subscription_id
         * @param int $access_record_id
         * @return UserSubscription
         * @throws DatabaseException
         */
        public function registerUserSubscription(int $account_id, int $subscription_id, int $access_record_id): UserSubscription
        {
            $account_id = (int)$account_id;
            $subscription_id = (int)$subscription_id;
            $access_record_id = (int)$access_record_id;
            $status = (int)UserSubscriptionStatus::Active;
            $created_timestamp = (int)time();

            $Query = QueryBuilder::insert_into('user_subscriptions', array(
                'account_id' => $account_id,
                'subscription_id' => $subscription_id,
                'access_record_id' => $access_record_id,
                'status' => $status,
                'created_timestamp' => $created_timestamp
            ));
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults == true)
            {
                return $this->getUserSubscription(UserSubscriptionSearchMethod::byAccountID, $account_id);
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error, $Query);
            }
        }
    }