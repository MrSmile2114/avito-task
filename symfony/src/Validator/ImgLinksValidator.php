<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Validation;

class ImgLinksValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $validator = Validation::createValidator();

        /* @var $constraint \App\Validator\ImgLinks */

        if (null === $value || '' === $value) {
            return;
        }

        $linksMin = $constraint->min;
        $linksMax = $constraint->max;

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        //Delimiter check
        if (!strpos($value, ',')) {
            $violations = $validator->validate($value, [new NotBlank(), new Url()]);
            if (
                (0 !== count($violations))  //necessary, otherwise accepts multiple links without delimiter:
                or (substr_count($value, 'https://') + substr_count($value, 'http://') > 1)
            ) {
                $this->context->buildViolation($constraint->messageDelimiterInvalid)->addViolation();

                return;
            }
        }

        $arrayLinks = explode(',', $value);
        //count links check:
        if (count($arrayLinks) > $linksMax) {
            $this->context->buildViolation($constraint->messageMaxLinks)
                ->setParameter('{{ value }}', $linksMax)
                ->addViolation();
        }
        if (count($arrayLinks) < $linksMin) {
            $this->context->buildViolation($constraint->messageMinLinks)
                ->setParameter('{{ value }}', $linksMax)
                ->addViolation();
        }

        //check links:
        foreach ($arrayLinks as $link) {
            $violations = $validator->validate($link, [new NotBlank(), new Url()]);
            foreach ($violations as $violation) {
                $this->context->addViolation($violation);
            }
        }

        return;
    }

}
