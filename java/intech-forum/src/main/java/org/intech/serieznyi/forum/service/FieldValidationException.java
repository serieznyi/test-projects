package org.intech.serieznyi.forum.service;

import lombok.Getter;

@Getter
public class FieldValidationException extends Exception {
    private final String fieldName;

    public FieldValidationException(String fieldName, String message) {

        super(message);
        this.fieldName = fieldName;
    }
}
