package org.intech.serieznyi.forum.dto;

import lombok.Data;
import org.intech.serieznyi.forum.domain.entity.User;
import org.intech.serieznyi.forum.validation.string.CompareStrings;
import org.intech.serieznyi.forum.validation.string.StringComparisonMode;
import org.intech.serieznyi.forum.validation.username.UsernameUnique;

import javax.validation.constraints.NotBlank;
import javax.validation.constraints.NotNull;
import javax.validation.constraints.Pattern;
import javax.validation.constraints.Size;

@Data
@CompareStrings(propertyNames = {"password","confirmPassword"}, matchMode= StringComparisonMode.EQUAL, message="{register_validation.passwords_not_equal}")
public class UserRegisterDto {
    @NotBlank
    @UsernameUnique
    @Size(min=5, max=15)
    String username;

    @NotBlank
    @Pattern(regexp = "^(?=.*[A-Z])(?=.*[a-z])(?=.*\\d)(?=.*[!@#$%]).*$", message = "{register_validation.password.regex}")
    String password;

    @NotBlank
    private String confirmPassword;

    @NotNull
    User.Role role;
}
