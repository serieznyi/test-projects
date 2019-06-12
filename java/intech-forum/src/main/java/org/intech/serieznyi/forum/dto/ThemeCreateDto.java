package org.intech.serieznyi.forum.dto;

import lombok.Getter;
import lombok.Setter;

import javax.validation.constraints.NotBlank;
import javax.validation.constraints.Size;

@Getter
@Setter
public class ThemeCreateDto {

    @NotBlank
    @Size(min=3, max=50)
    String title;

    @NotBlank
    @Size(min=5)
    String text;
}
