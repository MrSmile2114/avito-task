<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidOptionsException;

/**
 * @Annotation
 */
class ImgLinks extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $messageMaxLinks = 'Too many photo links. Max {{ value }} is allowed.';

    public $messageMinLinks = 'Too few photo links. Min {{ value }} is allowed.';

    public $messageDelimiterInvalid = 'Invalid URL or delimiter for URLs. Only "," is allowed.';

    public $messageInvalidProtocol = 'Links must have a protocol at the beginning. (http://, https://)';

    /**
     * @var integer
     */
    public $max;

    /**
     * @var integer
     */
    public $min;

    public function __construct($options = null)
    {
        //custom options:
        if (is_null($options) or !key_exists('min', $options) or is_null($options['min'])) {
            $this->min = 1;
        } elseif (is_int($options['min']) and $options['min'] >= 0) {
            $this->min = $options['min'];
        } else {
            throw new InvalidOptionsException('Option min is invalid', $options);
        }
        if (is_null($options) or !key_exists('max', $options) or is_null($options['max'])) {
            $this->max = 10;
        } elseif (is_int($options['max']) and $options['max'] > 0) {
            $this->max = $options['max'];
        } else {
            throw new InvalidOptionsException('Option max is invalid', $options);
        }

        parent::__construct($options);
    }

}
