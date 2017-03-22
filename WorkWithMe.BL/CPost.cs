﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL;

namespace WorkWithMe.BL
{
   public class CPost
    {
        public Guid Id { get; set; }
        public Guid OwnerUserId { get; set; }
        public Guid? TargetGroupId { get; set; }
        public string Title { get; set; }
        public string Content { get; set; }
        public bool IsSticky { get; set; }
        public bool IsDeleted { get; set; }
        public DateTime TimeStamp { get; set; }
        public DateTime? EventTimeStamp { get; set; }

        public CPost() { }

        public CPost(Guid ownerUserId, Guid? targetGroupId, string title, string content, bool isSticky, bool isDeleted, DateTime timestamp, DateTime? eventTimestamp)
        {
            OwnerUserId = ownerUserId;
            TargetGroupId = targetGroupId;
            Title = title;
            Content = content;
            IsSticky = isSticky;
            IsDeleted = isDeleted;
            TimeStamp = timestamp;
            EventTimeStamp = eventTimestamp;
        }

        public CPost(Guid id, Guid ownerUserId, Guid? targetGroupId, string title, string content, bool isSticky, bool isDeleted, DateTime timestamp, DateTime? eventTimestamp)
        {
            Id = id;
            OwnerUserId = ownerUserId;
            TargetGroupId = targetGroupId;
            Title = title;
            Content = content;
            IsSticky = isSticky;
            IsDeleted = isDeleted;
            TimeStamp = timestamp;
            EventTimeStamp = eventTimestamp; 
        }

        public int Create()
        {
            try
            {
                WorkWithMeDataContext oDC = new WorkWithMeDataContext();
                return oDC.spCreatePost(OwnerUserId, TargetGroupId, Title, Content, IsSticky, EventTimeStamp);
            }
            catch(Exception ex)
            {
                throw ex; 
            }
        }

        public void Update()
        {
            WorkWithMeDataContext oDC = new WorkWithMeDataContext();
            tblPost post = (from p in oDC.tblPosts where p.Id == Id select p).FirstOrDefault();

            post.Id = Id;
            post.OwnerUserId = OwnerUserId;
            post.TargetGroupId = TargetGroupId;
            post.Title = Title;
            post.Content = Content;
            post.IsSticky = IsSticky;
            post.IsDeleted = IsDeleted;
            post.TimeStamp = TimeStamp;
            post.EventTimeStamp = EventTimeStamp;

            oDC.SubmitChanges();

        }
        
        public void Delete()
        {
            WorkWithMeDataContext oDC = new WorkWithMeDataContext();

            tblPost post = (from p in oDC.tblPosts where p.Id == Id select p).FirstOrDefault();

            oDC.tblPosts.DeleteOnSubmit(post);
            oDC.SubmitChanges();
        }
    }

    public class CPostList : List<CPost>
    {
        public void LoadPostsForUser(Guid userId)
        {
            Clear();
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                List<spGetPostsForUserResult> list = oDC.spGetPostsForUser(userId).ToList();
                foreach (spGetPostsForUserResult item in list)
                {
                    CPost post = new CPost(item.OwnerUserId, item.TargetGroupId, item.Title, item.Content, item.IsSticky, item.IsDeleted, item.TimeStamp, item.EventTimeStamp);
                    Add(post);
                }
            }
        }
    }

}
