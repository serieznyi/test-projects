package org.intech.serieznyi.forum.domain.repository;

import org.intech.serieznyi.forum.domain.entity.Message;
import org.intech.serieznyi.forum.domain.entity.Theme;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.repository.CrudRepository;
import org.springframework.stereotype.Repository;

import java.util.UUID;

@Repository
public interface MessageRepository extends CrudRepository<Message, UUID> {
    Page<Message> findByTheme(Theme theme, Pageable pageable);
}
