package org.intech.serieznyi.forum.security.method;

import org.intech.serieznyi.forum.domain.entity.User;
import org.springframework.data.domain.Auditable;
import org.springframework.security.access.expression.SecurityExpressionRoot;
import org.springframework.security.access.expression.method.MethodSecurityExpressionOperations;
import org.springframework.security.core.Authentication;

import javax.validation.constraints.NotNull;
import java.time.LocalDateTime;
import java.time.temporal.TemporalAccessor;
import java.util.UUID;

public class CustomMethodSecurityExpressionRoot extends SecurityExpressionRoot implements MethodSecurityExpressionOperations {

    private Object filterObject;
    private Object returnObject;

    public CustomMethodSecurityExpressionRoot(Authentication authentication) {
        super(authentication);
    }

    public boolean isOwner(@NotNull Auditable<User, UUID, TemporalAccessor> object) {
        User currentUser = (User)this.authentication.getPrincipal();

        if (object.getCreatedBy().isPresent()) {
            return object.getCreatedBy().get().getId().equals(currentUser.getId());
        }

        return false;
    }

    @Override
    public void setFilterObject(Object filterObject) {
        this.filterObject = filterObject;
    }

    @Override
    public Object getFilterObject() {
        return filterObject;
    }

    @Override
    public void setReturnObject(Object returnObject) {
        this.returnObject = returnObject;
    }

    @Override
    public Object getReturnObject() {
        return returnObject;
    }

    @Override
    public Object getThis() {
        return this;
    }
}