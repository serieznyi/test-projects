package org.intech.serieznyi.forum.service;

import lombok.extern.log4j.Log4j2;
import lombok.extern.slf4j.Slf4j;
import org.intech.serieznyi.forum.domain.entity.Message;
import org.intech.serieznyi.forum.domain.repository.MessageRepository;
import org.intech.serieznyi.forum.event.MessageAddedEvent;
import org.springframework.context.ApplicationEventPublisher;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.stereotype.Service;

import javax.transaction.Transactional;

@Service
@Log4j2
public class MessageService {
    private MessageRepository messageRepository;
    private ApplicationEventPublisher applicationEventPublisher;

    MessageService(MessageRepository messageRepository, ApplicationEventPublisher applicationEventPublisher) {
        this.messageRepository = messageRepository;
        this.applicationEventPublisher = applicationEventPublisher;
    }

    @PreAuthorize("hasPermission(#message, 'create')")
    @Transactional
    public void createNew(Message message) {
        messageRepository.save(message);

        applicationEventPublisher.publishEvent(new MessageAddedEvent(message));
    }

    @PreAuthorize("hasPermission(#message, 'delete') or isOwner(#message)")
    public void delete(Message message) {
        messageRepository.delete(message);
    }
}
