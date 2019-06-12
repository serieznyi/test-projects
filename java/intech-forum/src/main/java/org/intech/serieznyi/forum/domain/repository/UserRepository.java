package org.intech.serieznyi.forum.domain.repository;

import org.intech.serieznyi.forum.domain.entity.User;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;
import java.util.UUID;

@Repository
public interface UserRepository extends JpaRepository<User, UUID> {
    Optional<User> findUserByUsername(String username);

    boolean existsUserByUsername(String username);

    User getByUsername(String username);
}
