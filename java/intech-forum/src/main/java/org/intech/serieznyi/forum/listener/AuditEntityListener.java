package org.intech.serieznyi.forum.listener;

import org.intech.serieznyi.forum.domain.entity.User;
import org.springframework.data.domain.Auditable;
import org.springframework.security.core.context.SecurityContextHolder;

import javax.persistence.PrePersist;
import javax.persistence.PreUpdate;
import java.time.LocalDateTime;
import java.util.UUID;

public class AuditEntityListener {
    @PrePersist
    private void beforeCreateOperation(Object object) {
        if (object instanceof Auditable) {
            final User user = (User) SecurityContextHolder.getContext().getAuthentication().getPrincipal();
            Auditable<User, UUID, LocalDateTime> audit = (Auditable<User, UUID, LocalDateTime>) object;

            audit.setCreatedDate(LocalDateTime.now());
            audit.setLastModifiedDate(LocalDateTime.now());
            audit.setCreatedBy(user);
            audit.setLastModifiedBy(user);
        }
    }

    @PreUpdate
    private void beforeUpdateOperation(Object object) {
        if (object instanceof Auditable) {
            final User user = (User) SecurityContextHolder.getContext().getAuthentication().getPrincipal();
            Auditable<User, UUID, LocalDateTime> audit = (Auditable<User, UUID, LocalDateTime>) object;

            audit.setLastModifiedDate(LocalDateTime.now());
            audit.setLastModifiedBy(user);
        }
    }
}
