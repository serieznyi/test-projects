package org.intech.serieznyi.forum.validation.username;

import javax.validation.Constraint;
import javax.validation.Payload;
import java.lang.annotation.Documented;
import java.lang.annotation.ElementType;
import java.lang.annotation.Retention;
import java.lang.annotation.Target;

import static java.lang.annotation.RetentionPolicy.RUNTIME;

@Target({ElementType.FIELD})
@Retention(RUNTIME)
@Constraint(validatedBy= UsernameUniqueValidator.class)
@Documented
public @interface UsernameUnique {
    String message() default "{validation.username_already_taken}";
    Class<?>[] groups() default {};
    Class<? extends Payload>[] payload() default {};
}