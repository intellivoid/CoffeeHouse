<?php


    namespace CoffeeHouse\Abstracts\CoreNLP;


    /**
     * Class NamedEntity
     * @package CoffeeHouse\Abstracts\CoreNLP
     */
    abstract class NamedEntity
    {
        const Person = "PERSON";

        const Location = "LOCATION";

        const Organization = "ORGANIZATION";

        const Miscellaneous = "MISC";


        const Money = "MONEY";

        const Number = "NUMBER";

        const Ordinal = "ORDINAL";

        const Percent = "PERCENT";


        const Date = "DATE";

        const Time = "TIME";

        const CurrentTime = "CURRENT_TIME";

        const Duration = "DURATION";

        const Set = "SET";


        const Email = "EMAIL";

        const Url = "URL";

        const City = "CITY";

        const StateOrProvince = "STATE_OR_PROVINCE";

        const Country = "COUNTRY";

        const Nationality = "NATIONALITY";

        const Religion = "RELIGION";

        const JobTitle = "JOB_TITLE";

        const Ideology = "IDEOLOGY";

        const CauseOfDeath = "CAUSE_OF_DEATH";

        const CriminalCharge = "CRIMINAL_CHARGE";

        const UsernameHandle = "USERNAME_HANDLE";

        const Unknown = "UNKNOWN";
    }