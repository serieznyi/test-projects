package org.intech.serieznyi.forum.validation.string;

import org.intech.serieznyi.forum.domain.repository.UserRepository;

import javax.validation.ConstraintValidator;
import javax.validation.ConstraintValidatorContext;
import javax.validation.ConstraintValidatorContext.ConstraintViolationBuilder;
import java.util.ArrayList;
import java.util.List;

public class CompareStringsValidator implements ConstraintValidator<CompareStrings, Object> {
    private String[] propertyNames;
    private StringComparisonMode comparisonMode;
    private boolean allowNull;

    @Override
    public void initialize(CompareStrings constraintAnnotation) {
        this.propertyNames = constraintAnnotation.propertyNames();
        this.comparisonMode = constraintAnnotation.matchMode();
        this.allowNull = constraintAnnotation.allowNull();
    }

    @Override
    public boolean isValid(Object target, ConstraintValidatorContext context) {
        boolean isValid = true;
        List<String> propertyValues = new ArrayList<String>(propertyNames.length);
        for(int i=0; i<propertyNames.length; i++) {
            String propertyValue = ConstraintValidatorHelper.getPropertyValue(String.class, propertyNames[i], target);
            if(propertyValue == null) {
                if(!allowNull) {
                    isValid = false;
                    break;
                }
            } else {
                propertyValues.add(propertyValue);
            }
        }

        if(isValid) {
            isValid = ConstraintValidatorHelper.isValid(propertyValues, comparisonMode);
        }

        if (!isValid) {
            /*
             * if custom message was provided, don't touch it, otherwise build the
             * default message
             */
            String message = context.getDefaultConstraintMessageTemplate();
            message = (message.isEmpty()) ?  ConstraintValidatorHelper.resolveMessage(propertyNames, comparisonMode) : message;

            context.disableDefaultConstraintViolation();

            for (String propertyName : propertyNames) {
                ConstraintViolationBuilder violationBuilder = context.buildConstraintViolationWithTemplate(message);
                violationBuilder.addPropertyNode(propertyName).addConstraintViolation();
            }
        }

        return isValid;
    }
}