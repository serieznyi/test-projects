package org.intech.serieznyi.forum.dto;

import lombok.Data;
import lombok.Getter;
import lombok.Setter;
import org.intech.serieznyi.forum.domain.entity.Theme;

import javax.validation.constraints.Min;
import javax.validation.constraints.NotBlank;
import javax.validation.constraints.NotNull;
import javax.validation.constraints.Size;
import java.util.UUID;

@Data
public class MessageCreateDto {
    @NotBlank
    String content;

    Theme theme;
}
