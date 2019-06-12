package org.intech.serieznyi.forum.config;

import org.intech.serieznyi.forum.security.method.CustomMethodSecurityExpressionHandler;
import org.intech.serieznyi.forum.security.evaluator.CustomPermissionEvaluator;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.access.expression.method.MethodSecurityExpressionHandler;
import org.springframework.security.config.annotation.method.configuration.EnableGlobalMethodSecurity;
import org.springframework.security.config.annotation.method.configuration.GlobalMethodSecurityConfiguration;

@Configuration
@EnableGlobalMethodSecurity(prePostEnabled = true)
public class MethodSecurityConfig extends GlobalMethodSecurityConfiguration {

    protected MethodSecurityExpressionHandler createExpressionHandler()
    {
        CustomMethodSecurityExpressionHandler expressionHandler = new CustomMethodSecurityExpressionHandler();

        expressionHandler.setPermissionEvaluator(new CustomPermissionEvaluator());

        return expressionHandler;
    }
}
