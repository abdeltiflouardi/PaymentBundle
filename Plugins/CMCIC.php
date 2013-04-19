<?php

namespace OS\PaymentBundle\Plugins;

use Symfony\Component\HttpFoundation\RedirectResponse;

class CMCIC
{

    /**
     *
     * @var string TPE Version (Ex : 3.0)
     */
    private $version;

    /**
     *
     * @var string TPE Number (Ex : 1234567)
     */
    private $tpeNumber;

    /**
     *
     * @var string Company code (Ex : companyname)
     */
    private $companyCode;

    /**
     *
     * @var string Language (Ex : FR, DE, EN, ..)
     */
    private $lang;

    /**
     *
     * @var string Return URL if transaction OK
     */
    private $urlOK;

    /**
     *
     * @var string Return URL if transaction error
     */
    private $urlKO;

    /**
     *
     * @var string Payment Server URL (Ex : https://ssl.paiement.cic-banques.fr/paiement.cgi)
     */
    private $uriPayment = 'https://ssl.paiement.cic-banques.fr';

    /**
     *
     * @var string Payment Server URL (Ex : https://ssl.paiement.cic-banques.fr/test/paiement.cgi)
     */
    private $uriDemoPayment = 'https://ssl.paiement.cic-banques.fr/test/';

    /**
     *
     * @var string
     */
    private $pathPayment = 'paiement.cgi';

    /**
     *
     * @var string
     */
    private $ctlhmac = 'V1.04.sha1.php--[CtlHmac%s%s]-%s';

    /**
     *
     * @var string
     */
    private $ctlhmacstr = 'CtlHmac%s%s';

    /**
     *
     * @var string
     */
    private $cgi2Receipt = "version=2\ncdr=%s";

    /**
     *
     * @var string
     */
    private $cgi2MacOk = '0';

    /**
     *
     * @var string
     */
    private $cgi2MacNotOk = "1\n";

    /**
     *
     * @var string
     */
    private $cgi2Fields = '%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*';

    /**
     *
     * @var string
     */
    private $cgi1Fields = '%s*%s*%s%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s';

    /**
     *
     * @var string The Key
     */
    private $key;

    /**
     *
     * @var \DateTime
     */
    private $date;

    /**
     *
     * @var float
     */
    private $amount;

    /**
     *
     * @var string
     */
    private $currency;

    /**
     *
     * @var string
     */
    private $reference;

    /**
     *
     * @var string
     */
    private $comment;

    /**
     *
     * @var string
     */
    private $email;

    /**
     *
     * @var integer
     */
    private $countEcheance;

    /**
     *
     * @var float
     */
    private $amountEcheance1;

    /**
     *
     * @var \DateTime
     */
    private $dateEcheance1;

    /**
     *
     * @var float
     */
    private $amountEcheance2;

    /**
     *
     * @var \DateTime
     */
    private $dateEcheance2;

    /**
     *
     * @var float
     */
    private $amountEcheance3;

    /**
     *
     * @var \DateTime
     */
    private $dateEcheance3;

    /**
     *
     * @var float
     */
    private $amountEcheance4;

    /**
     *
     * @var \DateTime
     */
    private $dateEcheance4;

    /**
     *
     * @var string
     */
    private $queryString;

    /**
     *
     * @var string
     */
    private $usableKey;

    /**
     *
     * @var boolean
     */
    private $isDemo;

    /**
     *
     * @var string 
     */
    private $returnUrl;

    /**
     *
     * @var string
     */
    private $returnSuccessUrl;

    /**
     *
     * @var string
     */
    private $returnErrorUrl;

    /**
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function __construct()
    {
        $this->setVersion('3.0');
        $this->setDate(new \DateTime());

        $this->isDemo = true;
    }

    /**
     * 
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function getTpeNumber()
    {
        return $this->tpeNumber;
    }

    public function setTpeNumber($tpeNumber)
    {
        $this->tpeNumber = $tpeNumber;
        return $this;
    }

    public function getCompanyCode()
    {
        return $this->companyCode;
    }

    public function setCompanyCode($companyCode)
    {
        $this->companyCode = $companyCode;
        return $this;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function setLang($lang)
    {
        $this->lang = strtoupper($lang);
        return $this;
    }

    public function getUrlOK()
    {
        return $this->urlOK;
    }

    public function setUrlOK($urlOK)
    {
        $this->urlOK = $urlOK;
        return $this;
    }

    public function getUrlKO()
    {
        return $this->urlKO;
    }

    public function setUrlKO($urlKO)
    {
        $this->urlKO = $urlKO;
        return $this;
    }

    public function getUriPayment()
    {
        return $this->isDemo ? $this->getUriDemoPayment() : $this->uriPayment;
    }

    public function setUriPayment($uriPayment)
    {
        $this->uriPayment = $uriPayment;
        return $this;
    }

    public function getUriDemoPayment()
    {
        return $this->uriDemoPayment;
    }

    public function setUriDemoPayment($uriDemoPayment)
    {
        $this->uriDemoPayment = $uriDemoPayment;
        return $this;
    }

    public function getPathPayment()
    {
        return $this->pathPayment;
    }

    public function setPathPayment($pathPayment)
    {
        $this->pathPayment = $pathPayment;
        return $this;
    }

    public function getCtlhmac()
    {
        return $this->ctlhmac;
    }

    public function setCtlhmac($ctlhmac)
    {
        $this->ctlhmac = $ctlhmac;
        return $this;
    }

    public function getCtlhmacstr()
    {
        return $this->ctlhmacstr;
    }

    public function setCtlhmacstr($ctlhmacstr)
    {
        $this->ctlhmacstr = $ctlhmacstr;
        return $this;
    }

    public function getCgi2Receipt()
    {
        return $this->cgi2Receipt;
    }

    public function setCgi2Receipt($cgi2Receipt)
    {
        $this->cgi2Receipt = $cgi2Receipt;
        return $this;
    }

    public function getCgi2MacOk()
    {
        return $this->cgi2MacOk;
    }

    public function setCgi2MacOk($cgi2MacOk)
    {
        $this->cgi2MacOk = $cgi2MacOk;
        return $this;
    }

    public function getCgi2MacNotOk()
    {
        return $this->cgi2MacNotOk;
    }

    public function setCgi2MacNotOk($cgi2MacNotOk)
    {
        $this->cgi2MacNotOk = $cgi2MacNotOk;
        return $this;
    }

    public function getCgi2Fields()
    {
        return $this->cgi2Fields;
    }

    public function setCgi2Fields($cgi2Fields)
    {
        $this->cgi2Fields = $cgi2Fields;
        return $this;
    }

    public function getCgi1Fields()
    {
        return $this->cgi1Fields;
    }

    public function setCgi1Fields($cgi1Fields)
    {
        $this->cgi1Fields = $cgi1Fields;
        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getCountEcheance()
    {
        return $this->countEcheance;
    }

    public function setCountEcheance($countEcheance)
    {
        $this->countEcheance = $countEcheance;
        return $this;
    }

    public function getAmountEcheance1()
    {
        return $this->amountEcheance1;
    }

    public function setAmountEcheance1($amountEcheance1)
    {
        $this->amountEcheance1 = $amountEcheance1;
        return $this;
    }

    public function getDateEcheance1()
    {
        return $this->dateEcheance1;
    }

    public function setDateEcheance1(\DateTime $dateEcheance1)
    {
        $this->dateEcheance1 = $dateEcheance1;
        return $this;
    }

    public function getAmountEcheance2()
    {
        return $this->amountEcheance2;
    }

    public function setAmountEcheance2($amountEcheance2)
    {
        $this->amountEcheance2 = $amountEcheance2;
        return $this;
    }

    public function getDateEcheance2()
    {
        return $this->dateEcheance2;
    }

    public function setDateEcheance2(\DateTime $dateEcheance2)
    {
        $this->dateEcheance2 = $dateEcheance2;
        return $this;
    }

    public function getAmountEcheance3()
    {
        return $this->amountEcheance3;
    }

    public function setAmountEcheance3($amountEcheance3)
    {
        $this->amountEcheance3 = $amountEcheance3;
        return $this;
    }

    public function getDateEcheance3()
    {
        return $this->dateEcheance3;
    }

    public function setDateEcheance3(\DateTime $dateEcheance3)
    {
        $this->dateEcheance3 = $dateEcheance3;
        return $this;
    }

    public function getAmountEcheance4()
    {
        return $this->amountEcheance4;
    }

    public function setAmountEcheance4($amountEcheance4)
    {
        $this->amountEcheance4 = $amountEcheance4;
        return $this;
    }

    public function getDateEcheance4()
    {
        return $this->dateEcheance4;
    }

    public function setDateEcheance4(\DateTime $dateEcheance4)
    {
        $this->dateEcheance4 = $dateEcheance4;
        return $this;
    }

    public function getQueryString()
    {
        return $this->queryString;
    }

    public function setQueryString($queryString)
    {
        $this->queryString = $queryString;
        return $this;
    }

    public function getUsableKey()
    {
        return $this->usableKey ? : $this->computeUsableKey();
    }

    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    public function getReturnSuccessUrl()
    {
        return $this->returnSuccessUrl;
    }

    public function setReturnSuccessUrl($returnSuccessUrl)
    {
        $this->returnSuccessUrl = $returnSuccessUrl;
        return $this;
    }

    public function getReturnErrorUrl()
    {
        return $this->returnErrorUrl;
    }

    public function setReturnErrorUrl($returnErrorUrl)
    {
        $this->returnErrorUrl = $returnErrorUrl;
        return $this;
    }

    public function getCgi1FieldsData()
    {
        return sprintf(
            $this->getCgi1Fields(),
            $this->getTpeNumber(),
            $this->getFormatedDate(),
            $this->getAmount(),
            $this->getCurrency(),
            $this->getReference(),
            $this->htmlEncode($this->getComment()),
            $this->getVersion(),
            $this->getLang(),
            $this->getCompanyCode(),
            $this->getEmail(),
            $this->getCountEcheance(),
            $this->getAmountEcheance1(),
            $this->getDateEcheance1(),
            $this->getAmountEcheance2(),
            $this->getDateEcheance2(),
            $this->getAmountEcheance3(),
            $this->getDateEcheance3(),
            $this->getAmountEcheance4(),
            $this->getDateEcheance4(),
            $this->getQueryString()
        );
    }

    public function getCgi2FieldsData()
    {
        return sprintf(
            $this->getCgi2Fields(),
            $this->getTpeNumber(),
            $this->getReturnVar('date'),
            $this->getReturnVar('montant'),
            $this->getReturnVar('reference'),
            $this->getReturnVar('texte-libre'),
            $this->getVersion(),
            $this->getReturnVar('code-retour'),
            $this->getReturnVar('cvx'),
            $this->getReturnVar('vld'),
            $this->getReturnVar('brand'),
            $this->getReturnVar('status3ds'),
            $this->getReturnVar('numauto'),
            $this->getReturnVar('motifrefus'),
            $this->getReturnVar('originecb'),
            $this->getReturnVar('bincb'),
            $this->getReturnVar('hpancb'),
            $this->getReturnVar('ipclient'),
            $this->getReturnVar('originetr'),
            $this->getReturnVar('veres'),
            $this->getReturnVar('pares')
        );
    }

    public function getFormatedDate()
    {
        return $this->date->format('d/m/Y:H:i:s');
    }

    public function getFormatedAmount()
    {
        return sprintf('%s%s', $this->getAmount(), $this->getCurrency());
    }

    public function computeUsableKey()
    {
        $hexStrKey = substr($this->getKey(), 0, 38);
        $hexFinal  = "" . substr($this->getKey(), 38, 2) . "00";

        $cca0 = ord($hexFinal);

        if ($cca0 > 70 && $cca0 < 97) {
            $hexStrKey .= chr($cca0 - 23) . substr($hexFinal, 1, 1);
        } else {
            if (substr($hexFinal, 1, 1) == "M") {
                $hexStrKey .= substr($hexFinal, 0, 1) . "0";
            } else {
                $hexStrKey .= substr($hexFinal, 0, 2);
            }
        }

        $this->usableKey = pack("H*", $hexStrKey);

        return $this->usableKey;
    }

    public function computeHmac($fields = null)
    {
        if (!$fields) {
            $fields = $this->getCgi1FieldsData();
        }

        return strtolower(hash_hmac("sha1", $fields, $this->getUsableKey()));

        // If you don't have PHP 5 >= 5.1.2 and PECL hash >= 1.1
        // you may use the hmac_sha1 function defined below
        //return strtolower($this->hmacSha1($this->getUsableKey(), $this->getCgi1FieldsData()));
    }

    public function hmacSha1($key, $data)
    {
        $length = 64; // block length for SHA1
        if (strlen($key) > $length) {
            $key    = pack("H*", sha1($key));
        }
        $key    = str_pad($key, $length, chr(0x00));
        $ipad   = str_pad('', $length, chr(0x36));
        $opad   = str_pad('', $length, chr(0x5c));
        $k_ipad = $key ^ $ipad;
        $k_opad = $key ^ $opad;

        return sha1($k_opad . pack("H*", sha1($k_ipad . $data)));
    }

    public function htmlEncode($data)
    {
        $SAFE_OUT_CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890._-";

        $result = "";
        for ($i = 0; $i < strlen($data); $i++) {
            if (strchr($SAFE_OUT_CHARS, $data{$i})) {
                $result .= $data{$i};
            } elseif (($var = bin2hex(substr($data, $i, 1))) <= "7F") {
                $result .= "&#x" . $var . ";";
            } else {
                $result .= $data{$i};
            }
        }

        return $result;
    }

    /**
     * @var string[]
     */
    public function getReturnVar($var)
    {
        $this->container->get('request')->get($var);
    }

    public function cmcicRedirect()
    {
        $params = array(
            'version'        => $this->getVersion(),
            'TPE'            => $this->getTpeNumber(),
            'date'           => $this->getFormatedDate(),
            'lgue'           => $this->getLang(),
            'montant'        => $this->getFormatedAmount(),
            'reference'      => $this->getReference(),
            'MAC'            => $this->computeHmac(),
            'societe'        => $this->getCompanyCode(),
            'texte-libre'    => $this->htmlEncode($this->getComment()),
            'mail'           => $this->getEmail(),
            'url_retour'     => $this->getReturnUrl(),
            'url_retour_ok'  => $this->getReturnSuccessUrl(),
            'url_retour_err' => $this->getReturnErrorUrl() ? : $this->getReturnUrl(),
            'nbrech'         => $this->getCountEcheance(),
            'dateech1'       => $this->getDateEcheance1(),
            'montantech1'    => $this->getAmountEcheance1(),
            'dateech2'       => $this->getDateEcheance2(),
            'montantech2'    => $this->getAmountEcheance2(),
            'dateech3'       => $this->getDateEcheance3(),
            'montantech3'    => $this->getAmountEcheance3(),
            'dateech4'       => $this->getDateEcheance4(),
            'montantech4'    => $this->getAmountEcheance4()
        );

        $url = sprintf('%s%s', $this->getUriPayment(), $this->getPathPayment());
        $url .= '?' . http_build_query($params);

        return new RedirectResponse($url);
    }

    public function getCodeReturn()
    {
        if ($this->computeHmac($this->getCgi2FieldsData()) == strtolower($this->getReturnVar('MAC'))) {
            return $this->getReturnVar('code-retour');
        }

        return null;
    }
}
