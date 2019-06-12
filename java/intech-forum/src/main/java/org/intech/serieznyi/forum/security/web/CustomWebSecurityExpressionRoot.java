package org.intech.serieznyi.forum.security.web;

import org.intech.serieznyi.forum.domain.entity.User;
import org.springframework.data.domain.Auditable;
import org.springframework.security.core.Authentication;
import org.springframework.security.web.FilterInvocation;
import org.springframework.security.web.access.expression.WebSecurityExpressionRoot;

import javax.validation.constraints.NotNull;
import java.time.temporal.TemporalAccessor;
import java.util.UUID;

public class CustomWebSecurityExpressionRoot extends WebSecurityExpressionRoot {
    public CustomWebSecurityExpressionRoot(Authentication a, FilterInvocation fi) {
        super(a, fi);
    }

    public boolean isOwner(@NotNull Auditable<User, UUID, TemporalAccessor> object) {
        User currentUser = (User)this.authentication.getPrincipal();

        if (object.getCreatedBy().isPresent()) {
            return object.getCreatedBy().get().getId().equals(currentUser.getId());
        }

        return false;
    }
}
