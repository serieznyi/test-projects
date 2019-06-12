package org.intech.serieznyi.forum.service;

import org.intech.serieznyi.forum.domain.entity.User;
import org.intech.serieznyi.forum.domain.repository.UserRepository;
import org.intech.serieznyi.forum.dto.UserRegisterDto;
import org.modelmapper.ModelMapper;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;

import java.util.Optional;

@Service
public class UserService implements org.springframework.security.core.userdetails.UserDetailsService {
    private UserRepository userRepository;
    private ModelMapper modelMapper;
    private PasswordEncoder passwordEncoder;

    UserService(UserRepository userRepository, ModelMapper modelMapper, PasswordEncoder passwordEncoder) {
        this.userRepository = userRepository;
        this.modelMapper = modelMapper;
        this.passwordEncoder = passwordEncoder;
    }

    @Override
    public UserDetails loadUserByUsername(String username) throws UsernameNotFoundException {
        Optional<User> userOptional = userRepository.findUserByUsername(username);

        if (!userOptional.isPresent()) {
            throw new UsernameNotFoundException("No user found with username: "+ username);
        }

        return userOptional.get();
    }

    public void register(UserRegisterDto form) {
        User user = new User();
        modelMapper.map(form, user);
        user.setPasswordHash(passwordEncoder.encode(form.getPassword()));

        userRepository.save(user);
    }
}
