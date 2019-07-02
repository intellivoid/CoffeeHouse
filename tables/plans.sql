create table plans
(
    id INT(255) AUTO_INCREMENT COMMENT 'The ID of this plan',
    active BOOL NULL COMMENT 'Indicates if this plan is currently active or not',
    account_id INT(255) NULL COMMENT 'The Account ID that this plan is associated with',
    access_key_id INT(255) NULL COMMENT 'The ID of the Access Key that''s associated with this plan',
    plan_type INT(255) NULL COMMENT 'The plan type that this plan is currently active on',
    promotion_code VARCHAR(255) NULL COMMENT 'If any promotion code was used upon activating this plan',
    monthly_calls INT(255) NULL COMMENT 'The amount of calls that this API plan is allowed to make (0 = Unlimited)',
    price_per_cycle FLOAT NULL COMMENT 'The amount that this plan requires after each billing cycle',
    next_billing_cycle INT(255) NULL COMMENT 'The Unix Timestamp for when this plan''s next billing cycle is gonna be raised',
    billing_cycle INT(255) NULL COMMENT 'The interval for each billing cycle',
    payment_required BOOL NULL COMMENT 'If set to true while the plan is active, this will not allow the user to make anymore requests
until a payment has been processed to continue the subscription',
    plan_created INT(255) NULL COMMENT 'The Unix Timestamp for when this plan record was first created',
    plan_started TINYINT(1) NULL COMMENT 'The Unix Timestamp for when this plan was last started with it''s subscription',
    PRIMARY KEY (id)
) COMMENT 'Table for hosting plan details regarding API Keys';
CREATE UNIQUE INDEX plans_id_uindex ON plans (id);