package org.intech.serieznyi.forum.validation.username;

import org.intech.serieznyi.forum.domain.repository.UserRepository;

import javax.validation.ConstraintValidator;
import javax.validation.ConstraintValidatorContext;

public class UsernameUniqueValidator implements ConstraintValidator<UsernameUnique, Object> {
    private UserRepository userRepository;

    UsernameUniqueValidator(UserRepository userRepository) {
        this.userRepository = userRepository;
    }

    @Override
    public boolean isValid(Object target, ConstraintValidatorContext context) {
        String username = (String) target;

        return username.isEmpty() || !userRepository.existsUserByUsername(username);
    }
}