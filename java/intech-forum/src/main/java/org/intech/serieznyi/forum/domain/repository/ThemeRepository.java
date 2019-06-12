package org.intech.serieznyi.forum.domain.repository;

import org.intech.serieznyi.forum.domain.entity.Theme;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;
import java.util.UUID;

@Repository
public interface ThemeRepository extends JpaRepository<Theme, UUID> {
    Optional<Theme> findByTitle(String title);
}
