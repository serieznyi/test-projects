package org.intech.serieznyi.forum.listener;

import org.intech.serieznyi.forum.domain.entity.User;
import org.springframework.data.domain.Auditable;

import javax.persistence.PrePersist;
import javax.persistence.PreUpdate;
import java.time.LocalDateTime;
import java.util.Date;

public class UserAuditEntityListener {
    @PrePersist
    private void beforeCreateOperation(Object object) {
        if (object instanceof User) {
            User audit = (User) object;
            audit.setCreatedDate(LocalDateTime.now());
            audit.setLastModifiedDate(LocalDateTime.now());
        }
    }

    @PreUpdate
    private void beforeUpdateOperation(Object object) {
        if (object instanceof User) {
            User audit = (User) object;
            audit.setLastModifiedDate(LocalDateTime.now());
        }
    }
}
