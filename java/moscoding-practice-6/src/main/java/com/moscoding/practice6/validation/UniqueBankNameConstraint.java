package com.moscoding.practice6.validation;

import javax.validation.Constraint;
import javax.validation.Payload;
import java.lang.annotation.Documented;
import java.lang.annotation.Retention;
import java.lang.annotation.Target;

import static java.lang.annotation.ElementType.*;
import static java.lang.annotation.RetentionPolicy.RUNTIME;

@Target({TYPE})
@Retention(RUNTIME)
@Constraint(validatedBy=UniqueBankNameValidator.class)
@Documented
public @interface UniqueBankNameConstraint {
    boolean allowNull() default false;
    String message() default "{validation.field_value.already_used}";
    Class<?>[] groups() default {};
    Class<? extends Payload>[] payload() default {};
}