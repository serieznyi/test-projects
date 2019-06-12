package org.intech.serieznyi.forum.domain.entity;

import lombok.Data;
import lombok.EqualsAndHashCode;
import org.intech.serieznyi.forum.listener.UserAuditEntityListener;
import org.springframework.data.annotation.CreatedDate;
import org.springframework.data.annotation.LastModifiedDate;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.UserDetails;

import javax.persistence.*;
import javax.validation.constraints.NotBlank;
import javax.validation.constraints.NotNull;
import javax.validation.constraints.Pattern;
import javax.validation.constraints.Size;
import java.time.LocalDateTime;
import java.util.*;

@EqualsAndHashCode(callSuper = false)
@EntityListeners(UserAuditEntityListener.class)
@Entity
@Table(name = "\"user\"")
@Data
public class User implements UserDetails {
    public enum Role {
        ROLE_ADMIN,
        ROLE_USER
    }

    public enum Privilege {
        THEME_CREATE,
        THEME_DELETE,

        MESSAGE_CREATE,
        MESSAGE_DELETE,
    }

    private static Map<Role, List<Privilege>> rolePrivileges = new HashMap<Role, List<Privilege>>() {{
        put(Role.ROLE_USER, Arrays.asList(
                Privilege.THEME_CREATE,
                Privilege.MESSAGE_CREATE
        ));

        put(Role.ROLE_ADMIN, Arrays.asList(
                Privilege.THEME_CREATE,
                Privilege.THEME_DELETE,

                Privilege.MESSAGE_CREATE,
                Privilege.MESSAGE_DELETE
        ));
    }};

    @Id
    @GeneratedValue(strategy = GenerationType.AUTO)
    private UUID id;

    @NotBlank
    @Size(min=5, max=15)
    @Pattern(regexp = "[a-z0-9]+", message = "{register_validation.password.regex}")
    String username;

    @NotBlank
    @Pattern(regexp = "^(?=.*[A-Z])(?=.*[a-z])(?=.*\\d)(?=.*[!@#$%]).*$", message = "{register_validation.password.regex}")
    String passwordHash;

    @NotNull
    @Enumerated(EnumType.STRING)
    Role role;

    @NotNull
    @CreatedDate
    private LocalDateTime createdDate;

    @NotNull
    @LastModifiedDate
    private LocalDateTime lastModifiedDate;

    @Override
    public boolean isAccountNonExpired() {
        return true;
    }

    @Override
    public boolean isAccountNonLocked() {
        return true;
    }

    @Override
    public boolean isCredentialsNonExpired() {
        return true;
    }

    @Override
    public boolean isEnabled() {
        return true;
    }

    @Override
    public Collection<? extends GrantedAuthority> getAuthorities() {
        return getGrantedAuthorities(rolePrivileges.get(role));
    }

    private List<GrantedAuthority> getGrantedAuthorities(List<Privilege> privileges) {
        List<GrantedAuthority> authorities = new ArrayList<>();
        for (Privilege privilege : privileges) {
            authorities.add(new SimpleGrantedAuthority(privilege.toString()));
        }
        return authorities;
    }

    public String getPassword() {
        return passwordHash;
    }
}
