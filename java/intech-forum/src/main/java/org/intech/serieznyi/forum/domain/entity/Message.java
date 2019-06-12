package org.intech.serieznyi.forum.domain.entity;

import lombok.Data;
import lombok.EqualsAndHashCode;
import org.intech.serieznyi.forum.listener.AuditEntityListener;
import org.springframework.data.jpa.domain.AbstractAuditable;

import javax.persistence.*;
import javax.validation.constraints.NotBlank;
import java.util.UUID;

@EqualsAndHashCode(callSuper = false)
@Data
@Entity
@Table(name = "message")
@EntityListeners({AuditEntityListener.class})
final public class Message extends AbstractAuditable<User, UUID> {
    @Id
    @GeneratedValue(strategy = GenerationType.AUTO)
    private UUID id;

    @NotBlank
    private String content;

    @ManyToOne
    @JoinColumn(name = "theme_id")
    private Theme theme;
}
