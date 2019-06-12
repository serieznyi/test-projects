package org.intech.serieznyi.forum.service;

import org.intech.serieznyi.forum.domain.entity.Theme;
import org.intech.serieznyi.forum.domain.repository.ThemeRepository;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.stereotype.Service;

import java.util.Optional;

@Service
public class ThemeService {
    private ThemeRepository themeRepository;

    ThemeService(ThemeRepository themeRepository) {
        this.themeRepository = themeRepository;
    }

    @PreAuthorize("hasPermission(#theme, 'create')")
    public Theme create(Theme theme) throws FieldValidationException {
        Optional<Theme> themeFromDb = themeRepository.findByTitle(theme.getTitle());

        if (themeFromDb.isPresent()) {
            throw new FieldValidationException("title", "exception.theme.title_already_taken");
        }

        themeRepository.save(theme);

        return theme;
    }

    @PreAuthorize("hasPermission(#theme, 'delete')")
    public void delete(Theme theme) {
        themeRepository.delete(theme);
    }
}
