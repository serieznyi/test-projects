package com.moscoding.practice6.validation;

import com.moscoding.practice6.domain.entity.Bank;
import com.moscoding.practice6.domain.repository.BankRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import javax.validation.ConstraintValidator;
import javax.validation.ConstraintValidatorContext;
import java.util.Optional;

@Service
public class UniqueBankNameValidator implements ConstraintValidator<UniqueBankNameConstraint, Object> {
    @Autowired
    private BankRepository bankRepository;
    private String message;

    public UniqueBankNameValidator() {
    }

    @Override
    public void initialize(UniqueBankNameConstraint constraintAnnotation) {
        this.message = constraintAnnotation.message();
    }

    @Override
    public boolean isValid(Object value, ConstraintValidatorContext context) {
        Bank bankForValidation = (Bank) value;
        Optional<Bank> bankFromDbOptional = bankRepository.findByName(bankForValidation.getName());

        boolean isValid = bankFromDbOptional
                .map(bank -> bank.getId().equals(bankForValidation.getId()))
                .orElse(true)
                ;

        if (!isValid) {
            context.disableDefaultConstraintViolation();
            ConstraintValidatorContext.ConstraintViolationBuilder violationBuilder = context.buildConstraintViolationWithTemplate(message);
            violationBuilder.addPropertyNode("name").addConstraintViolation();
        }

        return isValid;
    }
}
