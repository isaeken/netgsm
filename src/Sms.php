<?php


namespace IsaEken\NetGSM;


use DateTimeInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use IsaEken\NetGSM\Enums\SmsEncoding;
use IsaEken\NetGSM\Enums\SmsFilter;
use IsaEken\NetGSM\Exceptions\InvalidRequestException;
use IsaEken\NetGSM\Exceptions\MaxCharactersExceededException;
use IsaEken\NetGSM\Exceptions\NotAuthorizedException;
use IsaEken\NetGSM\Exceptions\NotAuthorizedHeaderException;

/**
 * Class Sms
 * @package IsaEken\NetGSM
 * @property string $username
 * @property string $password
 * @property string $header
 * @property string $message
 * @property string|string[] $gsm
 * @property string $dealer_code
 * @property string $encoding
 * @property string $filter
 * @property DateTimeInterface $start_date
 * @property DateTimeInterface $stop_date
 * @method null|string getUsername()
 * @method setUsername(string $username)
 * @method null|string getPassword()
 * @method setPassword(string $password)
 * @method null|string getHeader()
 * @method setHeader(string $header)
 * @method null|string getMessage()
 * @method setMessage(string $message)
 * @method null|string|string[] getGsm()
 * @method setGsm($gsm)
 * @method null|string getDealerCode()
 * @method setDealerCode(string $dealer_code)
 * @method null|string getEncoding()
 * @method setEncoding(string $encoding)
 * @method null|string getFilter()
 * @method setFilter(string $filter)
 * @method null|DateTimeInterface getStartDate()
 * @method setStartDate(DateTimeInterface $start_date)
 * @method null|DateTimeInterface getStopDate()
 * @method setStopDate(DateTimeInterface $stop_date)
 */
class Sms extends NetGSM
{
    /**
     * @var Client $client
     */
    public Client $client;

    /**
     * @var string $endpoint
     */
    public string $endpoint = "https://api.netgsm.com.tr/sms/send/get";

    /**
     * Sms constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->client = new Client($this->config);
    }

    /**
     * @return string
     * @throws InvalidRequestException
     * @throws MaxCharactersExceededException
     * @throws NotAuthorizedException
     * @throws NotAuthorizedHeaderException
     * @throws GuzzleException
     */
    public function send() : string
    {
        $gsm = $this->getGsm();
        if (is_array($gsm)) {
            $gsm = implode(",", $gsm);
        }

        $query = [
            "user_code" => $this->getUsername(),
            "password" => $this->getPassword(),
            "gsmno" => $gsm,
            "message" => $this->getMessage(),
            "msgheader" => $this->getHeader(),
            "startdate" => $this->getStartDate()->format("ddMMyyyyHHmm"),
            "stopdate" => $this->getStopDate()->format("ddMMyyyyHHmm"),
            "dil" => $this->getEncoding(),
            "filter" => $this->getFilter(),
            "bayikodu" => $this->getDealerCode(),
        ];

        $query = http_build_query($query);
        $request = $this->client->request("GET", $this->endpoint . "?" . $query);
        $response = $request->getBody()->getContents();

        if ($response == "20") {
            throw new MaxCharactersExceededException;
        }
        else if ($response == "30") {
            throw new NotAuthorizedException;
        }
        else if ($response == "40") {
            throw new NotAuthorizedHeaderException;
        }
        else if ($response == "70") {
            throw new InvalidRequestException;
        }

        return $response;
    }
}
