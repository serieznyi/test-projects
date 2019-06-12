package org.intech.serieznyi.forum.domain.entity;

import lombok.Data;
import lombok.EqualsAndHashCode;
import org.intech.serieznyi.forum.listener.AuditEntityListener;
import org.springframework.data.jpa.domain.AbstractAuditable;

import javax.persistence.*;
import javax.validation.constraints.NotBlank;
import javax.validation.constraints.Size;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.UUID;

@EqualsAndHashCode(callSuper = false)
@Data
@Entity
@Table(name = "theme")
@EntityListeners(AuditEntityListener.class)
final public class Theme extends AbstractAuditable<User, UUID> {
    @Id
    @GeneratedValue(strategy = GenerationType.AUTO)
    private UUID id;

    @NotBlank
    @Size(min=3, max=50)
    private String title;

    @NotBlank
    @Size(min = 20)
    private String text;

    @OneToMany(
            mappedBy = "theme",
            orphanRemoval = true,
            cascade = {CascadeType.REMOVE},
            fetch = FetchType.LAZY
    )
    private List<Message> messages = new ArrayList<>();

    @Temporal(TemporalType.TIMESTAMP)
    private Date lastActivityDate = new Date();
}
