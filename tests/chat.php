<?php

    use CoffeeHouse\Bots\Cleverbot;
    use CoffeeHouse\Exceptions\BotSessionException;

    $SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    include_once($SourceDirectory . 'CoffeeHouse' . DIRECTORY_SEPARATOR . 'CoffeeHouse.php');

    function getInput(): string
    {
        print(" > ");
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        fclose($handle);
        return $line;
    }

    $CoffeeHouse = new \CoffeeHouse\CoffeeHouse();
    $Bot = new Cleverbot();


    /** @var \CoffeeHouse\Objects\ForeignSession $Session */
    $Session = null;

    while(true)
    {
        try
        {
            if($Session == null)
            {
                $Bot->createSession('en', $Session);
            }
            else
            {

                $Bot->createSession('en',
                    array(
                        'headers' => $Session->Headers,
                        'cookies' => $Session->Cookies,
                        'vars' => $Session->Variables,
                        'language' => $Session->Language
                    )
                );
            }

            $Response = $Bot->think(getInput());
            print("Bot: " . $Response->getOutput() . "\n");

            if($Session == null)
            {
                $Session = $CoffeeHouse->getForeignSessionsManager()->createSession(
                    $Response->getSession()['headers'],
                    $Response->getSession()['cookies'],
                    $Response->getSession()['vars'],
                    $Response->getSession()['language']
                );
            }
            else
            {
                $Session->Headers = $Response->getSession()['headers'];
                $Session->Cookies = $Response->getSession()['cookies'];
                $Session->Variables = $Response->getSession()['vars'];
                $Session->Language = $Response->getSession()['language'];
                $Session->Messages += 1;
                $CoffeeHouse->getForeignSessionsManager()->updateSession($Session);
                $Session = $CoffeeHouse->getForeignSessionsManager()->getSession(
                    \CoffeeHouse\Abstracts\ForeignSessionSearchMethod::bySessionId, $Session->SessionID
                );
            }
            print("Session ID: " . $Session->SessionID . "\n");
        }
        catch(BotSessionException $botSessionException)
        {
            print("Error!\n");
            var_dump($botSessionException->getErrorDetails());
            exit(255);
        }
    }