package org.intech.serieznyi.forum.util;

import org.springframework.context.MessageSource;
import org.springframework.stereotype.Service;

import java.util.Locale;

@Service
public class Translator {
    private MessageSource messageSource;
    private Locale locale;

    Translator(MessageSource messageSource, Locale locale) {
        this.messageSource = messageSource;
        this.locale = locale;
    }

    public String translate(String message)
    {
        return messageSource.getMessage(message, new String[]{}, locale);
    }
}
