package org.intech.serieznyi.forum.listener.application;

import org.intech.serieznyi.forum.domain.entity.Message;
import org.intech.serieznyi.forum.domain.entity.Theme;
import org.intech.serieznyi.forum.domain.repository.ThemeRepository;
import org.intech.serieznyi.forum.event.MessageAddedEvent;
import org.springframework.context.event.EventListener;
import org.springframework.stereotype.Component;

import java.util.Date;

@Component
public class UpdateThemeLastActivityDateListener {
    private ThemeRepository themeRepository;

    UpdateThemeLastActivityDateListener(ThemeRepository themeRepository) {
        this.themeRepository = themeRepository;
    }

    @EventListener
    public void afterMessageAdded(MessageAddedEvent event) {
        Object object = event.getSource();

        if (object instanceof Message) {
            Theme theme = ((Message) object).getTheme();

            theme.setLastActivityDate(new Date());

            themeRepository.save(theme);
        }
    }
}
