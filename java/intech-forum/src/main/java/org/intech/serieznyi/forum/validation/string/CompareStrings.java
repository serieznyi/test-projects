package org.intech.serieznyi.forum.validation.string;

import javax.validation.Constraint;
import javax.validation.Payload;
import java.lang.annotation.Documented;
import java.lang.annotation.Retention;
import java.lang.annotation.Target;

import static java.lang.annotation.ElementType.TYPE;
import static java.lang.annotation.RetentionPolicy.RUNTIME;

@Target({TYPE})
@Retention(RUNTIME)
@Constraint(validatedBy= CompareStringsValidator.class)
@Documented
public @interface CompareStrings {
    String[] propertyNames();
    StringComparisonMode matchMode() default StringComparisonMode.EQUAL;
    boolean allowNull() default false;
    String message() default "";
    Class<?>[] groups() default {};
    Class<? extends Payload>[] payload() default {};
}