<?php declare(strict_types=1);

namespace App\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Validation;

/**
 * Trait Timestamps
 * @package App\Traits;
 */
trait Validations
{
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @throws \Exception
     */
    public function validate()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
        $errors = $validator->validate($this);

        if (count($errors) > 0) {
            throw new \Exception($errors, 422);
        }
    }
}
