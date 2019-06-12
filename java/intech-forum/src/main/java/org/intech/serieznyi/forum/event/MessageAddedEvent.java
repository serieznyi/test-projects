package org.intech.serieznyi.forum.event;

import org.intech.serieznyi.forum.domain.entity.Message;
import org.springframework.context.ApplicationEvent;

public class MessageAddedEvent extends ApplicationEvent {
    public MessageAddedEvent(Message message) {
        super(message);
    }
}
