package org.intech.serieznyi.forum.security.web;

import org.springframework.security.access.expression.SecurityExpressionOperations;
import org.springframework.security.authentication.AuthenticationTrustResolver;
import org.springframework.security.authentication.AuthenticationTrustResolverImpl;
import org.springframework.security.core.Authentication;
import org.springframework.security.web.FilterInvocation;
import org.springframework.security.web.access.expression.DefaultWebSecurityExpressionHandler;

public class CustomWebSecurityExpressionHandler extends DefaultWebSecurityExpressionHandler {
    private AuthenticationTrustResolver trustResolver = new AuthenticationTrustResolverImpl();

    @Override
    protected SecurityExpressionOperations createSecurityExpressionRoot(
            Authentication authentication,
            FilterInvocation fi
    ) {
        CustomWebSecurityExpressionRoot root = new CustomWebSecurityExpressionRoot(authentication, fi);
        root.setPermissionEvaluator(getPermissionEvaluator());
        root.setTrustResolver(trustResolver);
        root.setRoleHierarchy(getRoleHierarchy());
        String defaultRolePrefix = "ROLE_";
        root.setDefaultRolePrefix(defaultRolePrefix);

        return root;
    }
}
