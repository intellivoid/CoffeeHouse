<?php


    namespace CoffeeHouse\Abstracts\CoreNLP;


    /**
     * Class NamedEntity
     * @package CoffeeHouse\Abstracts\CoreNLP
     */
    abstract class NamedEntity
    {
        const Person = "person";

        const Location = "location";

        const Organization = "organization";

        const Miscellaneous = "misc";


        const Money = "money";

        const Number = "number";

        const Ordinal = "ordinal";

        const Percent = "percent";


        const Date = "date";

        const Time = "time";

        const CurrentTime = "current_time";

        const Duration = "duration";

        const Set = "set";


        const Email = "email";

        const Url = "url";

        const City = "city";

        const StateOrProvince = "state_or_province";

        const Country = "country";

        const Nationality = "nationality";

        const Religion = "religion";

        const JobTitle = "job_title";

        const Ideology = "ideology";

        const CauseOfDeath = "cause_of_death";

        const CriminalCharge = "criminal_charge";

        const UsernameHandle = "username_handle";

        const Unknown = "unknown";
    }